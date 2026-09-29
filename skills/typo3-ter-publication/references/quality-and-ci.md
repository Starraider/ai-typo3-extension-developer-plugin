# Local quality checks and CI

Run commands from the extension root. The [Composer example](../templates/composer.json) targets TYPO3 14.3; adapt dependencies and PHP/TYPO3 matrices for the extension before installing. Keep runtime requirements in `require`, tools in `require-dev`, and generated `vendor/` out of Git.

## Configure once

Copy [phpcs.xml.dist](../templates/phpcs.xml.dist) and [phpstan.neon.dist](../templates/phpstan.neon.dist) to the extension root. The PHPCS example checks PSR-12 as an additional baseline. TYPO3's maintained `typo3/coding-standards` package provides PHP-CS-Fixer rules, not a PHPCS ruleset. If an extension already has a tested TYPO3 PHPCS ruleset, use that instead and retain the PHPCS command. Avoid the retired `friendsoftypo3/phpstan-typo3`; the sample `saschaegerer/phpstan-typo3:^3.1` requires TYPO3 14. For TYPO3 13, resolve a compatible package version or run PHPStan with core annotations alone.

Set up TYPO3's rule files and PHPUnit environment:

```bash
composer install --no-interaction --prefer-dist
composer exec -- typo3-coding-standards setup extension
composer require --dev friendsoftypo3/kickstarter:^0.4
vendor/bin/typo3 make:testenv example_extension
```

The kickstarter command is one documented way to generate `Build/phpunit/UnitTests.xml`, `FunctionalTests.xml`, and their bootstraps. Run it on a branch after checking for an existing test environment, then review its diff and commit the project-specific test configuration. If an existing test environment uses `Build/Scripts/runTests.sh`, keep that setup and its matching commands. Remove the kickstarter dependency after generation if the project does not need it during normal development. Add unit tests under `Tests/Unit/` for isolated logic and functional tests under `Tests/Functional/` for TCA, persistence, routing, or service registration. Functional test classes should extend `TYPO3\TestingFramework\Core\Functional\FunctionalTestCase` and load the extension; unit tests can extend `TYPO3\TestingFramework\Core\Unit\UnitTestCase`.

## Run before a release

```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer dump-autoload --optimize
find Classes Configuration Tests -name '*.php' -print0 | xargs -0 -n1 php -l
vendor/bin/phpcs --standard=phpcs.xml.dist
vendor/bin/phpcbf --standard=phpcs.xml.dist
vendor/bin/phpcs --standard=phpcs.xml.dist
vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/php-cs-fixer fix
vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --memory-limit=1G
vendor/bin/phpunit -c Build/phpunit/UnitTests.xml
typo3DatabaseDriver=pdo_sqlite vendor/bin/phpunit -c Build/phpunit/FunctionalTests.xml
find Resources/Private/Language -name '*.xlf' -print0 | xargs -0 -n1 xmllint --noout
```

Use the documented level 5 in the PHPStan example, raise it when the codebase can pass, and keep the level visible in CI. Fix findings in source or explain a narrow baseline entry. Use SQLite for the sample functional run; add MariaDB or PostgreSQL runs if the extension uses database behavior that differs by driver. Run a real installation and upgrade smoke test for each claimed TYPO3 major version.

For Git, inspect tracked generated files with `git ls-files vendor .Build var .phpunit.cache`. A typical extension `.gitignore` excludes `/vendor/`, `/.Build/`, `/var/`, `/.phpunit.cache/`, `/.php-cs-fixer.cache`, and local `.env` files. Do not ignore source documentation or compiled public assets that the extension needs at runtime.

## GitHub Actions examples

- [CI workflow](../templates/github-actions-ci.yml) runs Composer validation, PHP lint, PHPCS, TYPO3 PHP-CS-Fixer, PHPStan, unit tests, functional tests, XML checks, and a Composer build/autoload step. It assumes the generated PHPUnit configuration files are committed.
- [TER release workflow](../templates/github-actions-ter-release.yml) publishes a validated numeric tag with Tailor. Copy it only after the maintainer has enabled automatic tag releases, protected release tags, and stored `TYPO3_API_TOKEN` as a GitHub Actions secret.

Review action versions and the PHP/TYPO3 matrix at adoption time. Test a candidate tag against a private fork or dry run before enabling an external TER upload.

## Sources

- [TYPO3 extension testing](https://docs.typo3.org/m/typo3/reference-coreapi/14.3/en-us/Testing/ExtensionTesting.html)
- [TYPO3 running unit tests](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/Testing/UnitTesting/Running.html)
- [TYPO3 running functional tests](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/Testing/FunctionalTesting/Running.html)
- [TYPO3 coding standards package](https://github.com/TYPO3/coding-standards)
- [Retired FriendsOfTYPO3 PHPStan extension](https://github.com/FriendsOfTYPO3/phpstan-typo3)
- [TYPO3-aware PHPStan extension](https://github.com/sascha-egerer/phpstan-typo3)
