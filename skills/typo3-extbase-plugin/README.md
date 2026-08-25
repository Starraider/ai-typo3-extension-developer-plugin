# TYPO3 Extbase plugin

Build and extend TYPO3 v13 and v14 Extbase frontend plugins with a coherent schema, persistence model, controller layer, backend registration, and Fluid view.

## What this skill solves

It prevents partially wired Extbase features by coordinating database columns, TCA, domain models, repositories, controllers, dependency injection, plugin registration, TypoScript, and templates in dependency order. It also covers relations, frontend-user ownership, CRUD, and non-standard persistence mappings.

## Use when

- Creating an Extbase frontend plugin or adding a model, repository, controller, action, relation, or CRUD flow.
- Configuring custom-table TCA, `sys_category`, `fe_users`, files, `ObjectStorage`, plugin registration, or controller visibility.
- Diagnosing persistence mapping, stale cacheability, missing templates, or frontend authorization issues.

Do not use it for a standalone FlexForm, TYPO3 Scheduler task, or record localization change; use the focused related skill instead.

## Expected outputs

- Coordinated PHP, SQL, TCA, TypoScript, Fluid, and service-registration changes in the authorized extension source.
- A verification summary covering wiring, cacheability, persistence mappings, authorization checks, and any unavailable runtime checks.

## Context requirements

- A TYPO3 v13 or v14 extension source tree, its extension key, vendor namespace, and the requested plugin/model/action behavior.
- Existing schema, TCA, services, registration, and templates when modifying an extension.
- An authorized TYPO3/PHP environment for schema, cache, backend, or frontend checks; static review remains possible without it.

## Installation

Install the complete Agent Plugin through a compatible client. For standalone use, place this directory in the client's project skill location. The portable `SKILL.md` is the runtime source. `agents/openai.yaml` supplies optional Codex presentation metadata.

## Supported clients

The skill uses only the portable Agent Skills frontmatter and is intended for any compatible client. Codex presentation metadata is included but is not required by other clients.

## Example prompts

- “Add a list/show Extbase plugin for `tx_shop_domain_model_product`, including TCA, repository, controller, registration, TypoScript, and Fluid templates.”
- “Update this frontend CRUD controller so users can edit only their own records and writes are non-cacheable; check the model, TCA, Services.yaml, and registration.”
- “The `sys_category` relation throws an Extbase mapping error. Inspect the model, repository, and persistence mapping and repair the smallest complete set of files.”

## Validation

Run the `new-skill` validator and the reference validator when available:

```bash
scripts/validate-skill.sh path/to/typo3-extbase-plugin --strict-portable
skills-ref validate path/to/typo3-extbase-plugin
```

Then run the extension's PHP/static checks and, when authorized, schema update, cache flush, backend insertion, and frontend CRUD/authorization checks. The plugin-level validator should also pass.

## Related skills

- [TYPO3 FlexForms](../typo3-flexforms/README.md) for standalone FlexForm XML and registration.
- [TYPO3 Translatable Extension Data](../typo3-translatable-extension-data/README.md) for localized custom records and language-aware queries.
- TYPO3 Scheduler Task is unrelated unless the extension request also adds a Scheduler task.

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](../../LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
