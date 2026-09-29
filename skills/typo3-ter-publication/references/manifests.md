# Manifest field notes

The [composer.json example](../templates/composer.json) and [ext_emconf.php example](../templates/ext_emconf.php) describe one extension, `acme/example-extension` with key `example_extension`, version `1.0.0`, TYPO3 14.3, and PHP 8.2+. Replace every example identity and test the selected constraints before using them. JSON does not allow comments, so keep the field explanations here.

| Field | What to set and verify |
| --- | --- |
| `name` | Composer vendor and dashed package name. The vendor must be controlled by the publisher; register the package on Packagist separately if Composer installation should be available there. |
| `type` | `typo3-cms-extension` for a third-party extension. |
| `description` | Accurate public title and summary. TYPO3 14 derives the title and summary from the text around ` - ` in Composer mode. |
| `license` | Use the actual license, normally `GPL-2.0-only` or `GPL-2.0-or-later`; include the matching LICENSE file and inspect third-party asset licenses. |
| `authors`, `homepage`, `support` | Actual maintainer and working URLs. Use an email that can receive security reports. |
| `require.php`, `require.typo3/cms-core` | Specific tested compatible ranges. Add required TYPO3 system or third-party extensions here. Do not use `*` for a release dependency. |
| `require-dev` | Only test, static analysis, style, and release tools. The example versions fit a TYPO3 14.3 test environment as of September 2026; resolve again for later releases. `saschaegerer/phpstan-typo3:^3.1` requires TYPO3 14 and should be replaced or omitted for TYPO3 13. |
| `suggest` | Optional Composer packages with a human-readable reason. Mirror optional TYPO3 extensions in `ext_emconf.php` `constraints.suggests` with a tested range. |
| `autoload.psr-4` | Match the PHP namespace prefix to `Classes/` and verify capitalization in paths. `autoload-dev` maps test classes to `Tests/`. |
| `extra.typo3/cms.extension-key` | Exact TER extension key; it need not equal the Composer package name. |
| `extra.typo3/cms.version` | Exact release version for TYPO3 14.2+ Classic mode. Match `ext_emconf.php` and the numeric portion of the Git tag. Prefer this over top-level Composer `version`. |
| `extra.typo3/cms.Package.providesPackages` | Use `{}` when the extension bundles no plain Composer package for Classic mode. If it does bundle one, document and test its supplied package path and license. |
| `ext_emconf.php` `$EM_CONF[$_EXTKEY]` | Preserve `$_EXTKEY` for TER compatibility. Match title, description, author, version, and dependency intent. |
| `ext_emconf.php` `constraints` | Use TYPO3's range syntax for `typo3` and `php`; mirror required, conflicting, and suggested extensions. Composer constraints are more expressive, so test every claim rather than assuming these two syntaxes are equivalent. |

`ext_emconf.php` has no standard privacy declaration field. Put actual data handling in README and, if needed, a dedicated privacy document linked from it. Do not hide a policy in `description`.

The sample `ext_emconf.php` is a compatibility example, not a signal to place new registration logic there. TYPO3 14.2 deprecated it for runtime metadata; TER and some tools can still use it. TYPO3 14.3 also deprecated `ext_tables.php`. Review the release's compatibility target before removing either file.

## Source checks

- [TYPO3 composer.json reference](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/FileStructure/ComposerJson.html)
- [TYPO3 ext_emconf.php deprecation](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-108345-Deprecation-of-ext-emconf-php.html)
- [TYPO3 ext_tables.php deprecation](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109438-ExtTablesPhpInExtensions.html)
- [Tailor archive manifest rules](https://github.com/TYPO3/tailor#what-the-extension-archive-must-carry)
