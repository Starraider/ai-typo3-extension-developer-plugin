# TYPO3 translatable extension data

Make TYPO3 v13 and v14 custom extension records translatable and keep Extbase/frontend queries aligned with the active site language.

## What this skill solves

It coordinates localization SQL columns, TCA `ctrl` wiring, field synchronization choices, language-aware repository queries, controller assignment, and frontend overlay verification without inventing custom translation flags.

## Use when

- A custom table needs TYPO3 language columns, translated fields, or localization TCA.
- A repository or controller returns default-language records instead of overlays.
- A field needs deliberate `allowLanguageSynchronization` behavior or related records need the same language aspect.

Do not use it for Fluid/XLF label translation, standalone FlexForms, Scheduler tasks, or unrelated frontend localization.

## Expected outputs

- Coordinated SQL, TCA, repository, and controller changes for localized records.
- A verification summary covering translated records, synchronization, fallbacks, related entities, and any unavailable frontend checks.

## Context requirements

- A TYPO3 v13 or v14 extension, the custom table, fields that vary by language, and expected fallback behavior.
- Existing SQL, TCA, repositories, controllers, related child tables, site language configuration, and storage-page rules when modifying code.
- An authorized environment for schema updates and multilingual frontend checks; static review remains possible without it.

## Installation

Install the complete Agent Plugin through a compatible client. For standalone use, place this directory in the client's project skill location. The portable `SKILL.md` is authoritative. `agents/openai.yaml` supplies optional Codex presentation metadata.

## Supported clients

The skill uses portable Agent Skills fields and works with any compatible client. Codex presentation metadata is optional and does not change localization behavior.

## Example prompts

- “Make `tx_myext_domain_model_event` translatable in TYPO3 v14: update SQL, TCA, and the Extbase repository, then explain the fallback behavior.”
- “The French page still shows default-language records. Audit the repository query, language aspect, controller, and related child records.”
- “Add language synchronization only to the shared date and category relation fields; keep title and body independently translatable.”

## Validation

Run the `new-skill` validator and reference validator when available:

```bash
scripts/validate-skill.sh path/to/typo3-translatable-extension-data --strict-portable
skills-ref validate path/to/typo3-translatable-extension-data
```

Then, with authorization, update the schema, create a translated record, view the target-language page, and check synchronized fields and related records. Run the plugin-level validator too.

## Related skills

- [TYPO3 Extbase Plugin](../typo3-extbase-plugin/README.md) for the surrounding domain model, repository, controller, and plugin wiring.
- TYPO3 FlexForms and TYPO3 Scheduler Task are unrelated unless the request includes those separate concerns.

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](../../LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
