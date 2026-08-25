# TYPO3 Scheduler task

Create and migrate custom TYPO3 v14 Scheduler tasks using native task classes and TCA-driven `tx_scheduler_task` records.

## What this skill solves

It coordinates task classes, native TCA record types, persisted task fields, labels, validation, SQL, and migration-safe parameter mapping while avoiding deprecated additional-field-provider registration.

## Use when

- Creating or modifying a custom TYPO3 v14 Scheduler task.
- Registering a task type and task-specific fields on `tx_scheduler_task`.
- Migrating `AdditionalFieldProviderInterface` or old scheduler registration and preserving stored parameters.

Do not use it for generic CLI/cron jobs, Extbase frontend plugins, FlexForms, or record localization.

## Expected outputs

- A coordinated task class, TCA override, language labels, SQL columns, and parameter mapping.
- A verification summary for schema, cache, backend task editing, listing, and safe execution, including untested runtime steps.

## Context requirements

- A TYPO3 v14 extension with the Scheduler system extension, the task class or migration target, and its stored field names.
- Existing TCA, SQL, labels, and legacy registration when changing a task.
- An authorized CLI/backend environment for schema, cache, and task checks; never execute a task solely to validate its structure.

## Installation

Install the complete Agent Plugin through a compatible client. For standalone use, place this directory in the client's project skill location. The portable `SKILL.md` is authoritative. `agents/openai.yaml` supplies optional Codex presentation metadata.

## Supported clients

The skill uses portable Agent Skills fields and works with any compatible client. Codex presentation metadata is optional and does not alter task safety or authorization requirements.

## Example prompts

- “Create a TYPO3 v14 Scheduler task that stores an endpoint on `tx_scheduler_task`, with native TCA registration, SQL, labels, and an executable task class.”
- “Migrate this `AdditionalFieldProviderInterface` task to TYPO3 v14 TCA while keeping existing stored parameters readable.”
- “The task type appears in Scheduler but its custom field is not persisted. Audit the task class, TCA field name, SQL, and parameter mapping without running the task.”

## Validation

Run the `new-skill` validator and reference validator when available:

```bash
scripts/validate-skill.sh path/to/typo3-scheduler-task --strict-portable
skills-ref validate path/to/typo3-scheduler-task
```

Then, with authorization, run `ddev typo3 database:updateschema`, `ddev typo3 cache:flush`, inspect the Scheduler form, list the task, and execute only a safe fixture. Run the plugin-level validator as well.

## Related skills

- [TYPO3 Extbase Plugin](../typo3-extbase-plugin/README.md) for frontend plugin work, not Scheduler execution.
- TYPO3 FlexForms and TYPO3 Translatable Extension Data are separate concerns unless explicitly requested.

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](../../LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
