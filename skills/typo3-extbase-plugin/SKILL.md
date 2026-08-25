---
name: typo3-extbase-plugin
description: Build and extend TYPO3 v13+/v14+ Extbase frontend plugins across database schema, domain models, repositories, controllers, TCA, TypoScript, Fluid templates, dependency injection, and plugin registration. Use when creating an Extbase plugin or adding models, relations, CRUD actions, frontend-user ownership, or persistence mappings. Do not use for standalone FlexForms, Scheduler tasks, or record localization; use the corresponding focused skill.
license: CC-BY-4.0
compatibility: Requires a TYPO3 v13 or v14 extension codebase and its normal PHP/TYPO3 tooling; database and cache commands require an authorized project environment.
---

# TYPO3 Extbase plugin

Build Extbase frontend plugins in a TYPO3 v13 or v14 extension.

## Outcome

Produce a consistent, wired Extbase feature whose schema, persistence model, controller actions, backend registration, dependency injection, TypoScript, and Fluid templates agree. Keep standalone FlexForm authoring, Scheduler tasks, and localization architecture in their focused skills; link to those skills when the request crosses a boundary.

## Scope

Use this Skill when the request needs the plugin's domain and frontend layers to agree. Hand off a standalone FlexForm, Scheduler, or record-localization task to the matching Skill. Apply more than one Skill only when the requested feature genuinely spans those concerns.

## Establish the Contract

Before editing, inspect the existing extension structure and resolve the extension key, vendor namespace, table/model names, plugin name, actions, storage PID, authorization rules, TYPO3 version, and whether the change is greenfield or a migration.

Completion: the affected files, cacheability, ownership/authorization behavior, and required database or TCA changes are explicit. Ask only for ambiguity that would change the implementation.

## Quick Reference — File Touchpoints

Every Extbase plugin touches these files. Work through them **in this order** to avoid forward-reference errors:

| # | File | Purpose |
|---|------|---------|
| 1 | `ext_tables.sql` | DB schema for custom tables |
| 2 | `Configuration/TCA/tx_<ext>_domain_model_<model>.php` | Backend form config per model |
| 3 | `Classes/Domain/Model/<Model>.php` | PHP domain model |
| 4 | `Classes/Domain/Repository/<Model>Repository.php` | Repository class |
| 5 | `Configuration/Extbase/Persistence/Classes.php` | Persistence mapping (only for non-standard table names) |
| 6 | `Classes/Controller/<Name>Controller.php` | Controller with actions |
| 7 | `ext_localconf.php` | `configurePlugin()` — wire controllers + actions |
| 8 | `Configuration/TCA/Overrides/30_tt_content_<plugin>.php` | `registerPlugin()` — make it insertable |
| 9 | `Configuration/Services.yaml` | DI: mark controller `public: true` |
| 10 | `Configuration/Sets/<set>/TypoScript/plugin.typoscript` | View paths + settings |
| 11 | `Resources/Private/Templates/<Controller>/<Action>.html` | Fluid templates |

## Workflow

Apply the following order to new features. For a focused change, start at the earliest affected touchpoint and check downstream consumers before finishing.

### 1. Database Schema (`ext_tables.sql`)

Define columns TYPO3 does not auto-create. Convention: `tx_<extkey>_domain_model_<modelname>`.

```sql
CREATE TABLE tx_mysitepackage_domain_model_example (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY (uid),
    title varchar(255) DEFAULT '' NOT NULL,
    description text,
    -- relation counters (int, not the actual FK)
    images int(11) unsigned DEFAULT '0' NOT NULL,
    categories int(11) unsigned DEFAULT '0' NOT NULL,
    -- fe_user ownership: store uid, not object
    fe_user int(11) unsigned DEFAULT '0' NOT NULL
);
```

> [!IMPORTANT]
> For `ObjectStorage` relations (images, categories, inline children), the DB column stores a **count**, not a foreign key. TYPO3 manages the MM table or foreign_field internally.

### 2. TCA Configuration

See [references/tca-patterns.md](references/tca-patterns.md) for complete field-type examples including `type => 'category'`, `type => 'country'`, `type => 'file'`, `type => 'inline'`, and `type => 'select'` with `foreign_table => 'fe_users'`.

### 3. Domain Model

See [references/domain-model-patterns.md](references/domain-model-patterns.md) for property types, ObjectStorage relations, sys_category integration, and the Category shim pattern.

### 4. Repository

```php
<?php
declare(strict_types=1);
namespace Vendor\MySitePackage\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ExampleRepository extends Repository
{
}
```

Override `ENTITY_CLASSNAME` only when the repository must return a mapped model class (e.g., for `sys_category`):

```php
class CategoryRepository extends Repository
{
    public const ENTITY_CLASSNAME = \Vendor\MySitePackage\Domain\Model\Category::class;
}
```

### 5. Persistence Mapping (`Configuration/Extbase/Persistence/Classes.php`)

Only needed when a model maps to a table whose name does **not** follow the `tx_<ext>_domain_model_<name>` convention:

```php
<?php
declare(strict_types=1);

return [
    // Map to core sys_category table
    \Vendor\MySitePackage\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    // Map fe_users to a custom model
    \Vendor\MySitePackage\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    // Property name → DB column name mapping
    \Vendor\MySitePackage\Domain\Model\Blog::class => [
        'tableName' => 'tx_blog_domain_model_blog',
        'properties' => [
            'categories' => ['fieldName' => 'category'],
        ],
    ],
];
```

### 6. Controller

See [references/controller-patterns.md](references/controller-patterns.md) for the full CRUD pattern, frontend user access control via `Context`, CountryProvider integration, and `initializeAction` patterns for DateTimeConverter.

### 7. Plugin Registration (`ext_localconf.php`)

```php
<?php
declare(strict_types=1);
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'MySitePackage',                // Extension name (UpperCamelCase, no vendor)
    'ExampleList',                  // Plugin name (unique within extension)
    [
        \Vendor\MySitePackage\Controller\ExampleController::class => 'list, show',
    ],
    // Non-cacheable actions (forms, writes, user-specific)
    [
        \Vendor\MySitePackage\Controller\ExampleController::class => 'show',
    ]
);
```

**Rules:**

- First arg is extension name in **UpperCamelCase** without vendor prefix
- Cacheable = safe to cache the output; non-cacheable = must re-render every request
- Any action that writes data (`create`, `update`, `delete`) or shows user-specific content **must** be non-cacheable

### 8. Backend Registration (`Configuration/TCA/Overrides/30_tt_content_<plugin>.php`)

```php
<?php
declare(strict_types=1);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MySitePackage',
    'ExampleList',
    'Example List'          // Label shown in backend dropdown
);
```

### 9. Dependency Injection (`Configuration/Services.yaml`)

```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true
    public: false

  Vendor\MySitePackage\:
    resource: '../Classes/*'

  Vendor\MySitePackage\Controller\ExampleController:
    public: true
```

Every controller **must** be registered as `public: true`.

### 10. TypoScript Plugin Setup

```typoscript
plugin.tx_mysitepackage {
    view {
        templateRootPaths {
            10 = EXT:my_site_package/Resources/Private/Templates/
        }
        partialRootPaths {
            10 = EXT:my_site_package/Resources/Private/Partials/
        }
        layoutRootPaths {
            10 = EXT:my_site_package/Resources/Private/Layouts/
        }
    }
    settings {
        # Custom plugin settings accessible via {settings.key} in Fluid
        storagePid = 5
    }
}
```

### 11. Fluid Templates

Template path convention: `Resources/Private/Templates/<ControllerName>/<ActionName>.html`

Example for `list` action: `Resources/Private/Templates/Example/List.html`

## Verification

Check that every configured action has a matching controller method and template, every controller is discoverable through `Services.yaml`, the database columns match model properties and relations, plugin registration uses the intended cacheability, and persistence mappings cover non-standard tables. Run the project's PHP, TYPO3 schema, cache, and functional checks when available; otherwise report which checks could not run.

Completion: the feature is internally wired, no required file or mapping is missing, and verification results or limitations are recorded.

## Common Pitfalls

| Pitfall | Symptom | Fix |
|---------|---------|-----|
| Missing `public: true` in Services.yaml | Controller not found / 404 | Add controller entry to Services.yaml |
| Persistence mapping missing for sys_category | `InvalidClassException` | Create Category shim + Classes.php mapping |
| Cacheable action shows stale user data | Wrong user's data displayed | Move action to non-cacheable list |
| ObjectStorage not initialized | "Call to member function on null" | Initialize in `__construct()` + `initializeObject()` |
| DB column missing for relation | Count always 0 | Add `int(11) unsigned DEFAULT '0'` column in ext_tables.sql |
| `configurePlugin` vs `registerPlugin` confused | Plugin not insertable in backend | `configurePlugin` = ext_localconf.php; `registerPlugin` = TCA/Overrides |

## Safety

- Work only in the user-authorized TYPO3 source extension; do not edit an installed cache or generated package.
- Treat `ext_tables.sql`, TCA, PHP, TypoScript, and Fluid edits as project changes. Do not deploy, publish, or contact external services unless explicitly requested.
- Schema updates, cache flushes, and frontend/backend verification can mutate a database or cache. Run them only in the intended environment and with authorization; prefer a reversible or disposable environment for destructive tests.
- For frontend CRUD, enforce ownership and authorization before update/delete operations. Never infer access from a submitted UID alone.

## Resources

- [Controller patterns](references/controller-patterns.md) for actions, CRUD, frontend-user access, forms, and responses.
- [Domain model patterns](references/domain-model-patterns.md) for properties, relations, ObjectStorage, and core-table shims.
- [TCA patterns](references/tca-patterns.md) for field configuration and relation types.
