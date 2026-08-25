# AI TYPO3 Extension Developer Plugin

An Agent Plugins 1.0.0 package of reusable skills for TYPO3 v13 and v14 extension development. It provides portable Agent Skills only; it does not bundle an MCP server.

## Installation

- [Install the complete Agent Plugin](plugin-installation.md) in Codex, Cursor,
  GitHub Copilot, or Visual Studio Code.
- [Install individual Agent Skills](skill-installation.md) in Antigravity,
  OpenCode, Windsurf, Zed, Trae, or Qoder.

## Included skills

| Skill | Use it for |
| --- | --- |
| [TYPO3 Extbase Plugin](skills/typo3-extbase-plugin/README.md) | Extbase frontend plugins, including models, repositories, controllers, TCA, TypoScript, Fluid, and registration. |
| [TYPO3 FlexForms](skills/typo3-flexforms/README.md) | TYPO3 v14 FlexForm XML, registration, settings names, and repair of legacy definitions. |
| [TYPO3 Scheduler Task](skills/typo3-scheduler-task/README.md) | TYPO3 v14 Scheduler task classes, TCA record types, persisted task fields, and legacy migrations. |
| [TYPO3 Translatable Extension Data](skills/typo3-translatable-extension-data/README.md) | Localized custom records and language-aware Extbase queries for TYPO3 v13 and v14. |

## Package Layout

`plugin.json` is the portable Agent Plugins 1.0.0 manifest. Compatible clients discover Skills from immediate child directories of `skills/`; each `SKILL.md` is the operational source. Each Skill also has a local README and optional `agents/openai.yaml` metadata for Codex. That metadata is not part of the portable package contract.

## Validation

From the plugin root, run the Agent Plugin validator and validate every immediate child Skill:

```bash
python3 /path/to/agent-plugin-builder/scripts/validate_agent_plugin.py --strict .
for skill in skills/*; do
  /path/to/new-skill/scripts/validate-skill.sh "$skill" --strict-portable
done
```

These validators are provided by the Agent Plugin Builder and New Skill toolsets, not by this package. When available, also run `skills-ref validate` for each Skill and perform the TYPO3 runtime checks described in its README. Schema, cache, backend, frontend, and Scheduler checks require an authorized TYPO3 environment.

## Compatibility

The package follows [Agent Plugins 1.0.0](https://agent-plugins.org/specification) and the [Agent Skills specification](https://agentskills.io/specification). Individual skill descriptions state their TYPO3-version scope.

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
