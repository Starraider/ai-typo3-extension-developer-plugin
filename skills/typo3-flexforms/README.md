# TYPO3 FlexForms

Create and repair TYPO3 v14 FlexForms for Extbase plugins and plain content elements, including valid XML, direct registration, settings names, and category fields.

## What this skill solves

It keeps FlexForm XML and plugin registration compatible with TYPO3 v14 by removing obsolete `<TCEforms>` wrappers, using the `settings.` prefix for Extbase values, and selecting the correct registration API.

## Use when

- Adding plugin settings or a new `Configuration/FlexForms/*.xml` definition.
- Registering a FlexForm for an Extbase plugin or plain content element.
- Repairing a broken or legacy FlexForm, including category field configuration.

Do not use it for general TCA/model design, Scheduler tasks, or localization of database records.

## Expected outputs

- A valid FlexForm XML file with stable field names and the intended settings structure.
- Matching PHP/TCA registration and a verification summary for backend rendering and persistence.

## Context requirements

- A TYPO3 v14 extension, the target plugin/ctype key, and the desired fields and defaults.
- Existing registration and XML when repairing or migrating a deployed plugin.
- An authorized TYPO3 backend/cache environment for runtime rendering checks; XML and static registration checks can run without it.

## Installation

Install the complete Agent Plugin through a compatible client. For standalone use, place this directory in the client's project skill location. The portable `SKILL.md` is authoritative. `agents/openai.yaml` supplies optional Codex presentation metadata.

## Supported clients

The skill uses portable Agent Skills fields and works with any compatible client. Codex presentation metadata is optional and does not change the workflow.

## Example prompts

- “Add `settings.limit` and `settings.categories` to this TYPO3 v14 Extbase plugin and register the FlexForm directly.”
- “This FlexForm no longer renders after upgrading to TYPO3 v14. Remove legacy TCEforms syntax and preserve existing stored field names.”
- “Register a FlexForm for this plain content element; inspect the current `addPlugin` call and verify the XML path and backend fields.”

## Validation

Run the `new-skill` validator and reference validator when available:

```bash
scripts/validate-skill.sh path/to/typo3-flexforms --strict-portable
skills-ref validate path/to/typo3-flexforms
```

Also parse the XML, inspect registration arguments, and, when authorized, open existing and new records in the TYPO3 backend. Run the plugin-level validator as part of package checks.

## Related skills

- [TYPO3 Extbase Plugin](../typo3-extbase-plugin/README.md) for the surrounding plugin, model, controller, and registration workflow.
- TYPO3 Translatable Extension Data and TYPO3 Scheduler Task are unrelated unless the request includes those separate concerns.

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](../../LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
