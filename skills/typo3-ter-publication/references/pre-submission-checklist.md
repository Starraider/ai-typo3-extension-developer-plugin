# TER pre-submission checklist

Copy this checklist into the extension's release issue or pull request. Mark a line only after checking evidence for the release commit.

## Ownership and compatibility

- [ ] The TYPO3.org account can manage the reserved extension key; the maintainer has access to the source repository and release credentials.
- [ ] Git, Composer 2, required PHP versions, a database for functional tests, and a test TYPO3 installation are available.
- [ ] The claimed TYPO3 and PHP versions match `composer.json`, `ext_emconf.php`, CI, README, and tests.
- [ ] The Composer vendor and package name are controlled by the publisher; the TER listing points to the right repository and issue tracker.

## Package metadata and layout

- [ ] `composer validate --strict` passes, the package type is `typo3-cms-extension`, and `typo3/cms-core` has a tested constraint.
- [ ] The extension key, title, description, author, version, license, and dependencies agree across manifests and listing.
- [ ] `extra.typo3/cms.version` and `Package.providesPackages` support TYPO3 14.2+ Classic mode where relevant.
- [ ] Runtime dependencies use specific compatible ranges; optional packages are in `suggest`; licenses are compatible.
- [ ] `autoload.psr-4` matches real class namespaces and paths; no committed `vendor/` or generated files enter the release.
- [ ] `ext_localconf.php` and any `ext_tables.php` contain only registrations valid for the target TYPO3 versions.
- [ ] TCA definitions and overrides, backend modules, services, event listeners, and frontend plugins use current registration locations.
- [ ] Public assets and private templates are separated; `Resources/Public/Icons/Extension.svg` renders as the extension icon.
- [ ] XLIFF files parse, referenced label IDs exist, and translated labels are visible in the backend and frontend.

## Quality and security

- [ ] PHP syntax lint, TYPO3 PHP-CS-Fixer, configured PHPCS, PHPStan at the documented level, and PHPUnit unit/functional suites pass.
- [ ] CI tests each supported TYPO3 major version or the declared support is narrowed to what CI actually tests.
- [ ] Output is escaped, state-changing actions are authorized and CSRF-protected, and SQL uses the TYPO3 query builder or bound parameters.
- [ ] File uploads validate type and size, secrets are supplied at runtime, and third-party code/assets have reviewed licenses and advisories.
- [ ] README covers installation, configuration, upgrade path, data storage/transmission, retention/deletion, and security contact.
- [ ] CHANGELOG and documentation describe this version's changes, migrations, and deprecations.

## Release and TER

- [ ] `.gitignore` excludes build output, local secrets, caches, `vendor/`, and test databases; `git status` is clean at the release commit.
- [ ] The SemVer version is identical in the release manifests and annotated Git tag; the tag points to the tested commit.
- [ ] Tailor's package preview or the ZIP contains the intended extension root and excludes secrets, tests/build artifacts as appropriate, and oversized or disallowed files.
- [ ] The current TER upload path's file type and size rules were checked before submission.
- [ ] The TER upload comment, Composer package, description, tags, source, documentation, and support links are ready.
- [ ] After authorized publication, the TER key/version/listing and installation from TER were verified.
