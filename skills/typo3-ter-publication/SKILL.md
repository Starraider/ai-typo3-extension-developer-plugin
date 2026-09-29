---
name: typo3-ter-publication
description: Prepare, publish, and maintain TYPO3 extensions in the TYPO3 Extension Repository (TER). Use for TER readiness audits, release metadata, packaging, CI, submission, version releases, and maintenance. Use the documentation skill for a documentation-only task and implementation skills for feature development.
license: CC-BY-4.0
compatibility: Requires a TYPO3 extension source tree. Local checks need Git, Composer, PHP, and the project's development tools. TER publication needs an authorized TYPO3.org account and extension key.
---

# TYPO3 TER publication

Bring an extension to a tested, reviewable release, publish it to TER when authorized, and maintain subsequent releases. Treat the target TYPO3 versions and the currently documented TER behavior as release inputs; recheck them before publishing.

## 1. Establish the release contract

Inspect the extension root, Git status and remotes, `composer.json`, `ext_emconf.php`, CI, tests, documentation, and existing TER listing. Record the extension key, Composer package name and vendor, supported TYPO3/PHP versions, release version, owner, repository URL, account/key access, and whether the user asked for preparation, submission, or maintenance. Verify Git, PHP, Composer, and a test installation for each claimed TYPO3 major version. Check the current [TYPO3 and TER source links](references/ter-release.md) when a rule may have changed.

Complete this step when every release identity and compatibility claim is backed by a file, test, or explicit user choice. For a readiness-only request, report missing access as a checklist item and continue local work.

## 2. Repair manifests and source layout

Use [manifest examples and field notes](references/manifests.md) and adapt [composer.json](templates/composer.json) and [ext_emconf.php](templates/ext_emconf.php) to the actual extension. Keep extension key, version, title, dependencies, PHP/TYPO3 constraints, author, and GPL-compatible license consistent. `composer.json` needs a `typo3-cms-extension` type, `typo3/cms-core` requirement, PSR-4 mapping, and `extra.typo3/cms.extension-key`. For TYPO3 14.2+ Classic compatibility, include `extra.typo3/cms.version` and `Package.providesPackages`. Keep `ext_emconf.php` for TER/legacy tooling while supported versions need it; do not invent an `ext_emconf.php` privacy field.

Audit `ext_localconf.php`, `ext_tables.php`, and `Configuration/` against the target version. Register frontend Extbase plugins through `ExtensionUtility::configurePlugin()` in `ext_localconf.php` and `registerPlugin()` in `Configuration/TCA/Overrides/tt_content.php`. Register backend modules in `Configuration/Backend/Modules.php`, services and event listeners in `Configuration/Services.yaml`, new table TCA in `Configuration/TCA/<table>.php`, and overrides in `Configuration/TCA/Overrides/`. Check the actual API for any other registration; a generic `RegistrationUtility` is not a substitute for a documented TYPO3 API. `ext_tables.php` is deprecated in TYPO3 14.3; migrate its remaining registrations to the documented locations when the supported versions permit it.

Put public assets under `Resources/Public/`, private templates and language files under `Resources/Private/`, and the extension icon at `Resources/Public/Icons/Extension.svg`. A root `ext_icon.svg` is deprecated and does not work as the icon in TYPO3 13+. Keep a legacy copy only if an older supported tool demonstrably requires one. Validate each `.xlf` as XML, check its `LLL:EXT:` references and label IDs, and use an XLIFF version supported by every claimed TYPO3 version.

Complete this step when the manifests agree, Composer can autoload classes by namespace, required registration works in a test installation, and every referenced resource exists.

## 3. Validate quality, dependencies, security, and documentation

Use the [pre-submission checklist](references/pre-submission-checklist.md). Declare runtime dependencies and optional `suggest` entries with specific tested Composer constraints; keep development tools in `require-dev`. Review third-party licenses and Classic-mode delivery of non-TYPO3 packages. Keep `vendor/`, caches, and build output out of Git and the TER package.

Run `composer install`, `composer validate --strict`, PHP syntax lint, and the configured PHPCS ruleset. Fix PHPCS findings; use TYPO3's `typo3/coding-standards` PHP-CS-Fixer rules for the official TYPO3 coding standard. Run PHPStan at the documented project level and include a TYPO3-aware extension only if it supports the target major version; `friendsoftypo3/phpstan-typo3` is retired. Run PHPUnit unit and functional suites using the compatible `typo3/testing-framework`. Use [local commands and CI examples](references/quality-and-ci.md) and copy the matching templates only after adjusting versions and paths.

Review output escaping, authorization and CSRF protection, parameterized database queries, file uploads, secrets, and third-party code. Add a clear README privacy section saying what personal data the extension stores or transmits, retention/deletion behavior, and configuration needed for privacy compliance. Add installation and upgrade notes plus a CHANGELOG entry for this release. Test installation and upgrades on each claimed TYPO3 major version.

Complete this step when checks pass or each failure is documented with a concrete blocker; README, CHANGELOG, dependencies, CI matrix, and tested compatibility agree.

## 4. Freeze and inspect the release

Follow [release and packaging commands](references/ter-release.md). Choose patch, minor, or major SemVer based on changes. Set the version in both manifests, update docs and CHANGELOG, inspect `.gitignore`, and build or preview the package with Tailor when available. Check the archive contents, file types, individual file sizes, total size, license, and absence of secrets and build artifacts against the current TER upload response or published limits; do not assume a fixed TER size limit. A release ZIP is optional for Tailor `--path` and needed for upload paths that request an archive. Create an annotated Git tag only after the release commit and checks pass; use a single tag convention, with the numeric version matching the manifests.

Complete this step when the tag points to the tested commit and the archive or Tailor package preview contains only intended extension files.

## 5. Submit and verify when authorized

Use the [TER submission procedure](references/ter-release.md). Confirm ownership of the registered key and an access token with appropriate scope. TER accepts a web-form upload or Tailor REST publication; merely linking a Git repository does not submit a release. Set the TER listing's Composer package, repository, issue tracker, documentation URL, description, and relevant tags. Inspect the resulting TER version and listing, then test installation from the published package.

Registration, token creation, Git push, tag push, CI secret changes, and TER publication write to external systems. Prepare and validate locally first. Execute only the external actions the user has authorized, and report the exact remaining action when authorization or credentials are missing. A tag-triggered publishing workflow must remain opt-in until the owner accepts automatic TER releases.

Complete this step when TER shows the intended key/version and metadata, or when the release is locally ready with a specific external handoff.

## 6. Maintain the listing and release line

For each patch/minor/major release, repeat manifest, CI, security, documentation, package, tag, and TER checks. State a backport policy for supported TYPO3 branches; apply security fixes to every affected supported line, then publish each line's own tested version. Monitor TYPO3 and dependency advisories, issue reports, new TYPO3 releases, and the listing's compatibility claims. Keep README and CHANGELOG aligned with every release. Use [the maintenance procedure](references/ter-release.md).

Complete this step when each supported line has a named owner, version policy, security contact, and a reproducible next-release path.
