# TYPO3 extension documentation

Create, maintain, preview, validate, and publish documentation for a TYPO3 extension.

## What this skill solves

TYPO3 extension documentation has its own source layout and publishing path. This skill helps an agent create a full manual in `Documentation/`, choose reStructuredText or Markdown, render it with the TYPO3 theme, catch renderer warnings, prepare documentation CI, and publish it to docs.typo3.org when authorized.

It prefers a full reStructuredText manual for multi-page extension documentation. TYPO3 recommends that approach because it supports HTML and branded PDF output, cross-references, and the full set of theme elements. A Markdown manual or root README remains useful for a small extension.

## Use when

- Creating or updating `Documentation/`, `Documentation/guides.xml`, `Index.rst`, `Index.md`, a TYPO3 extension README, or documentation chapters.
- Previewing or troubleshooting documentation with the TYPO3 renderer container.
- Adding a minimal documentation test to GitHub Actions or GitLab CI.
- Preparing or executing an authorized docs.typo3.org publication, webhook setup, or `documentation-draft` preview.

Do not use this skill for extension implementation that has no documentation task. Combine it with a relevant TYPO3 implementation skill when a feature requires both code and documentation.

## Expected outputs

- An accurate extension manual or concise root README, with a valid TYPO3 Docs entry point and `guides.xml` when it uses `Documentation/`.
- A local render result at `Documentation-GENERATED-temp/Index.html` and a concise report of render checks or limitations.
- For authorized publication, verified prerequisites, host-specific webhook settings, and a resulting URL or delivery/approval status.

## Context requirements

- The extension root and its `composer.json`.
- Existing documentation, root README, repository URL and default branch when available.
- Docker or Podman for local rendering. Network access is required to pull the renderer image.
- For publication, a public GitHub, GitLab, or Bitbucket repository. TYPO3 Documentation Team approval is required for the first rendering.

## Installation

Install the complete Agent Plugin through a compatible client. For individual use, place this directory in a project skill location such as `.agents/skills/typo3-extension-documentation/`. The portable `SKILL.md` provides the behavior. `agents/openai.yaml` only improves Codex presentation.

## Example prompts

- "Create a full reST manual for this TYPO3 extension. Read composer.json and the extension configuration, document installation, setup, and usage, then render it locally."
- "Our extension has a `Documentation/` directory, but the TYPO3 renderer warns about a missing page. Repair the navigation and run the minimal render check."
- "Prepare this extension for docs.typo3.org. Verify the repository and TER prerequisites, create a `documentation-draft` publication plan, but do not configure the webhook or push anything yet."
- "Add a Markdown documentation manual for this small extension and a GitHub Actions job that fails on renderer warnings."

## Validation

Validate the skill itself from the plugin root:

```bash
/Users/svenkalbhenn/.codex/skills/new-skill/scripts/validate-skill.sh \
  skills/typo3-extension-documentation --strict-portable
skills-ref validate skills/typo3-extension-documentation
```

For an extension manual, render from the extension root:

```bash
mkdir -p Documentation-GENERATED-temp
docker run --rm --pull always -v "$(pwd)":/project \
  ghcr.io/typo3-documentation/render-guides:latest \
  --config=Documentation --no-progress --minimal-test
```

Then open `Documentation-GENERATED-temp/Index.html` and inspect navigation, a representative code example, and external or cross-reference links.

## Related skills

- [TYPO3 Extbase Plugin](../typo3-extbase-plugin/README.md) when an Extbase change also needs documentation.
- [TYPO3 FlexForms](../typo3-flexforms/README.md) when documenting or changing a FlexForm-backed feature.
- [TYPO3 Scheduler Task](../typo3-scheduler-task/README.md) when documenting or changing a Scheduler task.

## References

- [How to document an extension](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/WritingDocForExtension/Index.html)
- [File structure](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Reference/FileStructure.html)
- [Render documentation with the TYPO3 theme](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/RenderingDocs/Index.html)
- [Adding documentation](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/HowTo/Documentation.html)
- [Webhook setup](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/WritingDocForExtension/Webhook.html)

## License

This project and all contained Agent Skills are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](../../LICENSE).

Copyright (c) 2026 Sven Kalbhenn ([https://www.skom.de](https://www.skom.de)).
