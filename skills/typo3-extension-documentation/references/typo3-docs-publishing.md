# TYPO3 Docs reference

Use this reference when creating a manual, running the renderer, configuring CI, or preparing publication. It summarizes the official TYPO3 documentation linked below. Re-check the live pages before an external publishing action because webhook and approval rules may change.

## Manual formats and required files

For a full reStructuredText manual, keep these files in the extension root:

```text
composer.json
Documentation/
  guides.xml
  Index.rst
  Introduction/Index.rst
  Installation/Index.rst
  Configuration/Index.rst
  Usage/Index.rst
```

`Index.rst` is the entry point. Start it with the `start` anchor and include all chapters in a `toctree`. A root `README.md` or `README.rst` can link to the rendered manual but does not replace a full manual unless the extension documentation really fits in one file.

For Markdown, use `Documentation/Index.md`; each Markdown subdirectory needs its own `Index.md`. Set `input-format="md"`, `index-name="Index"`, and `automatic-menu="true"` on the `guides` element in `Documentation/guides.xml`.

Do not put symbolic links in `Documentation/`. The renderer walks the directory literally and links can break rendering.

The official extension guide notes a server-rendering problem when `guides.xml` starts with an XML declaration. Start the generated file directly with `<guides`.

## Initialize and render locally

Run these commands from the extension root containing `composer.json`.

Initialize a manual:

```bash
docker run --rm --pull always -v "$(pwd)":/project -it \
  ghcr.io/typo3-documentation/render-guides:latest init
```

Choose reStructuredText for the richer TYPO3 documentation format. Supply a main Site Set name and path when the extension has one so the initializer can generate configuration documentation.

Render a full preview:

```bash
docker run --rm --pull always -v "$(pwd)":/project -it \
  ghcr.io/typo3-documentation/render-guides:latest --config=Documentation
```

Open `Documentation-GENERATED-temp/Index.html`. Podman can replace Docker when it is available as a compatible container runtime.

Run a non-interactive renderer test:

```bash
mkdir -p Documentation-GENERATED-temp
docker run --rm --pull always -v "$(pwd)":/project \
  ghcr.io/typo3-documentation/render-guides:latest \
  --config=Documentation --no-progress --minimal-test
```

## CI examples

Add CI only on request. A minimal GitHub Actions job is:

```yaml
name: test documentation

on: [push, pull_request]

jobs:
  documentation:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@de0fac2e4500dabe0009e67214ff5f5447ce83dd
      - run: |
          mkdir -p Documentation-GENERATED-temp \
          && docker run --rm --pull always -v "$(pwd)":/project \
             ghcr.io/typo3-documentation/render-guides:latest \
             --config=Documentation --no-progress --minimal-test
```

The equivalent GitLab job uses the renderer image and invokes `/opt/guides/entrypoint.sh --config=Documentation --no-progress --minimal-test` after creating `Documentation-GENERATED-temp`.

## Publish to docs.typo3.org

Before asking for an external change, check all of these:

1. `composer.json` is valid.
2. The extension has a full `Documentation/` manual or a root `README.md` or `README.rst`.
3. The repository is publicly available on GitHub, GitLab, or Bitbucket.
4. The TER entry uses the same extension key as `composer.json` and references the repository.
5. The TYPO3 Documentation Team has approved the repository for its first rendering.

The normal development branch is `main`; its documentation URL is:

```text
https://docs.typo3.org/p/<vendor>/<package>/main/en-us/
```

`documentation-draft` renders an unindexed draft at the same path with `draft` in place of `main`. Pushes to either branch trigger a rendering for that branch. Use the full package name from `composer.json`, not the extension key, in the URL.

### Webhook settings

Configure only after explicit authorization. The endpoint is `https://docs-hook.typo3.org`.

| Host | Required settings |
| --- | --- |
| GitHub | Payload URL: endpoint; Content type: `application/json`; SSL verification enabled; event: only push; active. |
| GitLab | URL: endpoint; triggers: push events and tag push events. |
| Bitbucket | Title: `TYPO3 Docs`; URL: endpoint; active; trigger: repository push. |

For GitHub deliveries, `200` means the ping was accepted, `204` means a rendering was triggered, and `412` means the repository awaits first-time approval. Check tag-push deliveries separately when a released version does not appear.

## Primary sources

- [How to document an extension](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/WritingDocForExtension/Index.html)
- [File structure](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Reference/FileStructure.html)
- [Render documentation with the TYPO3 theme](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/RenderingDocs/Index.html)
- [Adding documentation](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/HowTo/Documentation.html)
- [Webhook](https://docs.typo3.org/m/typo3/docs-how-to-document/main/en-us/Howto/WritingDocForExtension/Webhook.html)
