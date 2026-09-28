---
name: typo3-extension-documentation
description: Create, revise, preview, validate, and publish documentation for a TYPO3 extension. Use when an extension needs a Documentation manual or root README documentation, local rendering, docs.typo3.org publication preparation, webhook setup, or documentation CI. Do not use for general TYPO3 extension implementation without documentation work.
license: CC-BY-4.0
compatibility: Requires a TYPO3 extension source tree with composer.json. Local preview and CI checks require Docker or Podman; publishing requires a public GitHub, GitLab, or Bitbucket repository and TYPO3 Documentation Team approval.
---

# TYPO3 extension documentation

Create and maintain documentation that renders correctly locally and can be published on docs.typo3.org.

## Scope

Use this Skill for the extension's user, administrator, or developer documentation. It owns documentation structure, source text, TYPO3 Docs configuration, local preview, render checks, and the preparation or authorized execution of publication steps.

Do not use it for application code, TYPO3 configuration, or a general repository README that is unrelated to an extension manual. Use the appropriate implementation skill alongside this one when a requested feature needs both code and documentation.

## 1. Establish the documentation contract

Inspect the extension root and its `composer.json`, existing `Documentation/`, root README, release branches, repository host, and current documentation URL. Determine the extension key, Composer package name, supported TYPO3 versions, source branch, intended reader, and whether the request creates, updates, previews, validates, or publishes the manual.

Default to a full `Documentation/` manual in reStructuredText for multi-page or long-lived extension documentation. Use a Markdown manual only when the user prefers it or a concise, automatically navigated manual is enough. Use a root `README.md` or `README.rst` alone only for genuinely small documentation.

Completion: the documentation format, entry point, public URL shape, target branch, and affected files are explicit. Ask only when a missing choice would materially change the manual or an external action.

## 2. Create or repair the manual

When starting a full manual, run the TYPO3 documentation renderer's `init` command from the extension root and choose reStructuredText unless the contract calls for Markdown. Confirm that `Documentation/guides.xml` starts with `<guides`, not an XML declaration, because the documented server-rendering workaround requires it.

If the renderer cannot be run, use the matching starter files in [templates/rst](templates/rst) or [templates/markdown](templates/markdown). Replace every double-braced template token with project facts before rendering. Preserve existing documentation structure when revising unless the user asks for a migration.

For a multi-page reST manual, maintain `Documentation/Index.rst` as the entry point, give it the `start` anchor, and include every navigable chapter in its `toctree`. For a Markdown manual, use `Documentation/Index.md` and configure `guides.xml` with `input-format="md"`, `index-name="Index"`, and `automatic-menu="true"`.

Write documentation from the extension's actual public behavior. Cover the sections users need, such as installation, configuration, usage, and upgrade or migration notes when applicable. Ensure commands, code samples, settings names, paths, and supported versions match the source tree.

Completion: the manual has a valid entry point and `guides.xml`; navigation reaches every new chapter; no placeholder remains; and every procedural claim is supported by the extension source or clearly marked as a user choice.

## 3. Preview and validate locally

Run the renderer from the extension root, the directory containing `composer.json`:

```bash
docker run --rm --pull always -v "$(pwd)":/project -it \
  ghcr.io/typo3-documentation/render-guides:latest --config=Documentation
```

Use `podman` in place of `docker` when that is the available compatible runtime. Inspect `Documentation-GENERATED-temp/Index.html` in a browser and resolve renderer errors, warnings, broken navigation, missing files, and incorrect links. Do not edit the generated directory as source.

For a non-interactive CI-quality check, create the generated directory if required, then run the renderer with `--no-progress --minimal-test`. Add the documented GitHub Actions or GitLab CI job only when the user asks to change CI configuration.

Completion: local HTML exists at `Documentation-GENERATED-temp/Index.html`, the rendered navigation and representative pages are readable, and the minimal test finishes without warnings. If Docker or Podman is unavailable, report that limitation and perform static structure and link checks instead.

## 4. Prepare or publish on docs.typo3.org

First verify the publication prerequisites: a valid `composer.json`; either a `Documentation/Index.rst` plus `Documentation/guides.xml`, a Markdown `Documentation/Index.md` plus `guides.xml`, or a root README; and a public repository on GitHub, GitLab, or Bitbucket. Check that the package name, extension key, TER entry, and repository reference agree.

Publication configures an external webhook and may push commits or create tags. Prepare the steps by default. Only configure the webhook, push, tag, or otherwise publish after the user explicitly authorizes that external change.

For an authorized setup, use `https://docs-hook.typo3.org` and the host-specific settings in [references/typo3-docs-publishing.md](references/typo3-docs-publishing.md). The TYPO3 Documentation Team must approve the repository before the first render. Use `main` for current development documentation. Use `documentation-draft` for a non-indexed draft render at the corresponding `/draft/en-us/` URL.

Completion: the public-render prerequisites and branch URL are verified; any external action has explicit authorization; and the user receives the resulting docs URL or a concrete approval/delivery status.

## Safety

- Edit only the authorized extension source. Do not alter `Documentation-GENERATED-temp/`, installed packages, or caches as source files.
- Pulling the renderer image requires network access. Confirm the project root before mounting it into a container.
- Webhook configuration, pushes, tags, CI edits, and publishing change external systems. Explain the exact repository and action, then obtain explicit authorization at that boundary.
- Never claim that docs.typo3.org has published a manual until a successful delivery and rendered URL provide evidence.

## Resources

- [Publication, preview, and CI reference](references/typo3-docs-publishing.md) for official commands, requirements, webhook values, delivery responses, and source links.
- [reStructuredText starter files](templates/rst) for a multi-page manual.
- [Markdown starter files](templates/markdown) for a concise auto-navigated manual.
