# TYPO3 TER publication

This skill helps an agent prepare a TYPO3 extension for the TYPO3 Extension Repository, publish an authorized release, and maintain later releases. It covers manifests, source layout, dependencies, security review, tests, CI, packaging, submission, and post-release verification.

## What this skill solves

TER publication crosses extension metadata, TYPO3 compatibility, repository packaging, and the listing itself. This skill checks those parts together so a released version matches what maintainers tested.

## Use when

- A pre-submission audit or repair of an extension intended for TER.
- Building CI and repeatable patch, minor, or major release steps.
- Publishing a release through TER's web form or Tailor when the maintainer authorizes the external action.
- Checking an existing TER listing or planning backports and ongoing support.

For documentation-only work, use [TYPO3 Extension Documentation](../typo3-extension-documentation/README.md). For implementing a frontend plugin, FlexForm, Scheduler task, or localization feature, use the matching implementation skill before this release check.

## Expected outputs

The agent uses the [pre-submission checklist](references/pre-submission-checklist.md) to report evidence and blockers, then edits the authorized extension as needed. It can prepare matching manifests, quality configuration, CI, release commands, and a clean package. An authorized publication ends with a verified TER key, version, listing, and installation check. A readiness-only task ends with a release package or concrete remaining steps.

The supplied examples target a fictional `example_extension` on TYPO3 14.3. Replace every identity, URL, constraint, and PHP/TYPO3 matrix cell before copying them into an extension. The [field notes](references/manifests.md) explain the Composer and `ext_emconf.php` templates. [Quality and CI](references/quality-and-ci.md) explains tool setup and local commands. [TER release](references/ter-release.md) covers account setup, packaging, Git, submission, and maintenance.

## Context requirements

Provide the extension root, intended extension key, release version or release goal, supported TYPO3/PHP versions, Git remote, current TER listing if any, and repository ownership. Local execution needs Git, Composer 2, compatible PHP, and a test TYPO3 environment. Functional tests need a database driver. TER submission needs an owned key and TYPO3.org credentials or a scoped API token. The skill keeps token handling out of the source tree.

## Installation

The target repository groups portable skills under `skills/`. For standalone use, copy this entire directory into the target client's Agent Skills location, such as `.agents/skills/typo3-ter-publication/` for Codex. The [plugin installation guide](../../plugin-installation.md) and [individual skill installation guide](../../skill-installation.md) list other client paths. The portable runtime is `SKILL.md`; `agents/openai.yaml` is Codex presentation metadata.

## Example prompts

- "Audit this TYPO3 14 extension for TER submission. Fix the manifests and package layout, run the local checks, and give me a checked release list."
- "Prepare version 1.4.2 of this published extension. Update the changelog, test TYPO3 13 and 14 compatibility, and make a reviewable tag plan."
- "Add GitHub Actions checks for PHP lint, TYPO3 coding style, PHPCS, PHPStan level 5, unit tests, and SQLite functional tests. Show me the release job too."
- "Publish the tested 1.4.2 tag of our owned key to TER with Tailor, then verify the public listing and installation."
- "A security fix affects our TYPO3 13 maintenance branch and TYPO3 14 main branch. Plan and release both backports with accurate compatibility metadata."

## Validation

Validate the skill package from the plugin root:

```bash
/Users/svenkalbhenn/.codex/skills/new-skill/scripts/validate-skill.sh skills/typo3-ter-publication --strict-portable
skills-ref validate skills/typo3-ter-publication
python3 /Users/svenkalbhenn/.codex/skills/new-plugin/scripts/validate_agent_plugin.py --strict .
```

For an extension, use the commands in [quality and CI](references/quality-and-ci.md), inspect a Tailor package, and test installation on every claimed TYPO3 major version. The example workflows require project-specific PHPUnit configuration and an authorized TER token before use.

## Related skills

- [TYPO3 Extension Documentation](../typo3-extension-documentation/README.md) for a full manual and docs.typo3.org publication.
- [TYPO3 Extbase Plugin](../typo3-extbase-plugin/README.md) for frontend plugin implementation.
- [TYPO3 Translatable Extension Data](../typo3-translatable-extension-data/README.md) for localized records and labels.

## License

This skill is licensed under [CC BY 4.0](../../LICENSE). Copyright (c) 2026 Sven Kalbhenn.
