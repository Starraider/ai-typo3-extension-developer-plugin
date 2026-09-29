# TER release and maintenance procedure

Use the [pre-submission checklist](pre-submission-checklist.md) before any external release action. These commands assume the extension root, an existing `origin` remote, an owned TER key `example_extension`, and a numeric tag `1.2.3`. Replace the identity and version with the actual release. Read the current TYPO3 documentation and Tailor help before a release because TER behavior and limits can change.

## 1. Account and key

Create or use a TYPO3.org account, sign in to [TER's My Extensions page](https://extensions.typo3.org/my-extensions), and reserve the key or confirm that the account owns it. A public Git repository and issue tracker make the extension supportable. Tailor can register a key with `vendor/bin/tailor ter:register example_extension` after authentication. Obtain a TER personal access token with the necessary `extension:read` and `extension:write` scopes. Store it in an environment variable or CI secret, never in Git or a release archive. Confirm access with:

```bash
vendor/bin/tailor ter:details example_extension
vendor/bin/tailor ter:versions example_extension
```

For CI, add `TYPO3_API_TOKEN` as a GitHub Actions repository secret and review its expiration and scope. Token creation, key registration, and secret changes are external writes.

## 2. Version and branch

Use SemVer: patch for compatible fixes, minor for compatible features, major for breaking changes. Choose a version that is new for this TER key. A practical branch policy is `main` for the next minor/major release and `support/13` for fixes on a TYPO3 13 line after main moves to TYPO3 14. Cherry-pick a tested security fix into each affected line, then release each line with its own next version. Record which supported line receives which fixes in README or CONTRIBUTING.

Example release branch and tag workflow:

```bash
git status --short
git switch main
git pull --ff-only origin main
git switch -c release/1.2
vendor/bin/tailor set-version 1.2.3
# Edit CHANGELOG.md, README.md, docs, dependency constraints, and CI as needed.
composer install --no-interaction --prefer-dist
composer validate --strict
git diff --check
git add composer.json ext_emconf.php CHANGELOG.md README.md
git commit -m "Release 1.2.3"
git tag -a 1.2.3 -m "Release 1.2.3"
git show --stat 1.2.3
```

Run the quality and installation checks on the tagged commit before pushing. If the project releases from `main`, merge the release branch and tag the resulting tested commit instead. Keep a single tag convention. With numeric tags, both manifests use `1.2.3`; if the repository uses `v1.2.3`, strip the `v` for TER and adjust CI.

## 3. Inspect the package

Check `.gitignore` and tracked files. A clean `git archive` is useful for inspection, but Tailor's own packaging rules decide what `ter:publish --path` submits.

```bash
git ls-files vendor .Build var node_modules
git archive --format=zip --output=/tmp/example_extension-1.2.3.zip 1.2.3
unzip -l /tmp/example_extension-1.2.3.zip
vendor/bin/tailor create-artefact 1.2.3 example_extension
```

Inspect the generated Tailor ZIP as well as the Git archive. Check file names and types, largest individual files, total archive size, root `composer.json`, optional `ext_emconf.php`, README, CHANGELOG, LICENSE, icon, documentation, and required compiled assets. Review current TER upload rules or the actual upload response for size and file type restrictions; no fixed limit is assumed here. Do not send archives containing `.env`, API tokens, `vendor/`, caches, fixtures with personal data, or unrelated build output. Tailor accepts an extension directory with `--path` and can also publish a local or remote ZIP with `--artefact`; a separately built release ZIP is not required for the directory route.

## 4. Publish and verify

After authorization, push the intended branch and the one release tag:

```bash
git push origin release/1.2
git push origin 1.2.3
```

Then choose one TER path:

```bash
# REST route: uses the extension root and TYPO3_API_TOKEN.
vendor/bin/tailor ter:publish 1.2.3 example_extension --path=. --comment="Release 1.2.3"

# Or upload a reviewed archive through Tailor.
vendor/bin/tailor ter:publish 1.2.3 example_extension --artefact=/tmp/example_extension-1.2.3.zip --comment="Release 1.2.3"
```

For manual submission, sign in to TER key management, open `My Extensions`, select `Upload` beside the owned key, and follow its current form. Supply the reviewed ZIP and release comment. TER validates the package's root manifest; it does not require a separate `ext_emconf.php` upload when the root `composer.json` supplies required metadata. If included, `ext_emconf.php` travels inside the package and its values must agree with Composer metadata.

Set or verify the listing's Composer package name, repository URL, issue tracker, documentation URL, description, and useful functional tags. Check the current `vendor/bin/tailor ter:update --help` or use the TER web UI for fields without confirmed command options. Tailor `ter:update` replaces the values supplied for a field, so read the current listing before changing tags. Check the published result:

```bash
vendor/bin/tailor ter:version 1.2.3 example_extension
vendor/bin/tailor ter:details example_extension
```

Open the public TER page and confirm the exact version, compatibility, links, author, and description. Install that release into a clean supported TYPO3 test installation and verify the extension loads. TER publication does not automatically publish a Packagist package or docs.typo3.org manual; use those separate publication processes if requested.

## 5. Ongoing maintenance

For each release, triage issues and security reports, compare TYPO3 and dependency advisories against the supported versions, and review new TYPO3 deprecations. Update the support matrix and CI before claiming a new core major version. Keep an owner and security contact for the key. Document end-of-support dates and the backport policy. For a security issue, follow the [TYPO3 Extension Security Policy](https://typo3.community/contribute/teams-committees/security/extension-security-policy): inform the TYPO3 Security Team, keep details confidential, make security-only patch releases on affected supported lines, and coordinate the TER upload and disclosure with the team. Update the advisory and CHANGELOG on the agreed schedule.

## Primary sources

- [Publish your extension in TER](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/HowTo/PublishExtension/PublishToTER/Index.html)
- [TYPO3 publication overview](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/HowTo/PublishExtension/)
- [Tailor commands and packaging](https://github.com/TYPO3/tailor)
- [T3Planet's manual upload walkthrough](https://t3planet.de/en/blog/ter-typo3-extensions/) shows the TER form and useful listing links. Its older password-based automation examples should not be copied; use scoped TER tokens with Tailor.
- [TYPO3 extension file structure](https://docs.typo3.org/m/typo3/reference-coreapi/13.4/en-us/ExtensionArchitecture/FileStructure/Index.html)
- [TYPO3 14.3 ext_tables.php migration](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109438-ExtTablesPhpInExtensions.html)
- [TYPO3 extension icon deprecation](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-98093-Ext_iconAsExtensionIconFileLocation.html)
- [TYPO3 14.3 system requirements](https://get.typo3.org/version/14)
