---
name: typo3-flexforms
description: Create and repair TYPO3 v14 FlexForms for Extbase plugins and plain content elements, including XML structure, plugin registration, settings prefixes, and category fields. Use when adding plugin settings, wiring a FlexForm XML file, or removing legacy TCEforms wrappers. Do not use for general TCA/model work, Scheduler tasks, or record localization.
license: CC-BY-4.0
compatibility: Requires a TYPO3 v14 extension codebase with backend registration and FlexForm XML support; validate XML and TCA in the authorized project environment.
---

# TYPO3 FlexForms

Create and repair FlexForms in TYPO3 v14.

## Outcome

Produce a valid TYPO3 v14 FlexForm XML definition and the matching registration so editors can see, save, and retrieve the intended settings. Keep the XML, `settings.` namespace, registration API, and backend TCA in sync.

## Workflow

1. Classify the target as an Extbase plugin or a plain content element, identify its plugin/ctype key, and inspect existing registration and XML before editing.

   Completion: the registration API, XML path, desired fields, and whether existing records must remain compatible are known.

## TYPO3 v14 rules

- Place field configuration directly below each element. TYPO3 no longer supports the legacy `<TCEforms>` wrapper.
- Prefix Extbase field names with `settings.`. That makes them available as `$this->settings['limit']` in controllers and `{settings.limit}` in Fluid.
- Pass the FlexForm XML path directly to `ExtensionUtility::registerPlugin()` or `ExtensionManagementUtility::addPlugin()` when the selected API supports it. Retire `addPiFlexFormValue()` and custom `showitem` workarounds only after checking the target registration.

## Workflow

1. Create or update the FlexForm XML in `Configuration/FlexForms/PluginName.xml`. Use direct field configuration under each element, prefix Extbase fields with `settings.`, and preserve compatible field names unless a migration is intentional.

   Completion: the XML has no `<TCEforms>` wrapper, every field has a valid type/configuration, and category fields follow the supported one-to-many behavior.

2. Register the FlexForm with the direct registration parameter for the chosen API. Remove obsolete `addPiFlexFormValue()` or custom `showitem` workarounds only after confirming they are no longer needed by the target TYPO3 version.

   Completion: the registration points to the real XML path and the plugin/ctype remains available in the backend.

3. Validate the result by parsing the XML, checking the PHP/TCA registration, and opening an existing and a new content record in the backend when the project environment is available.

   Completion: fields render and persist under the expected names, or the unavailable runtime check is reported.

## Safety

- Edit only the authorized TYPO3 extension source and preserve existing setting names when stored content depends on them.
- Do not remove legacy registration or rename fields without checking migration impact on existing records.
- Backend cache flushes and record edits are stateful project operations; run them only in the intended environment with authorization.

## References

For complete examples of configuration, including Extbase registration, plain plugin registration, XML file structure, and categories, see [references/examples.md](references/examples.md).
