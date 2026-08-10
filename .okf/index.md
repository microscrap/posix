---
okf_version: "0.2"
---

# microscrap/posix Knowledge Bundle

Package knowledge for `microscrap/posix` (POSIX bindings over **ext-posi**, v0.7.0).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** This bundle lives at the **package root** only — never under `src/`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Document the bindings-only package (helpers + enums over `Posi\System` / `Posi\Memory`). Do **not** invent ServiceProviders, Chassis/Core coupling, or Fabricate remaps here.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes` so Composer dist packages do not ship this bundle.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespace, helpers over ext-posi.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Published 0.7.x overview and docs site entrypoint.
* [Pair with protocol peers](orientation/pairing-protocol-peers.md) - uart / gpio / i2c / spi (and ftdi beside); gpio-framework above.

# Architecture

* [Helpers → Posi\* ext](architecture/helpers-posi-ext.md) - Call stack: helpers delegate to `Posi\System` / `Posi\Memory`.

# Conventions

* [1:1 extension wrap](conventions/one-to-one-extension-wrap.md) - Helpers → `Posi\*` only; no parallel APIs.
* [Enums for platform flags](conventions/enums-platform-flags.md) - `FileControlFlag` / `FcntlCommand`; int-backed, FULLY UPPERCASE.

# Traps

* [`function_exists` vs built-in posix](traps/function-exists-builtin-posix.md) - PHP `posix` extension may win for overlapping names.
* [Platform flag values differ](traps/platform-flag-values-differ.md) - Shipped enum integers are Linux/glibc; other OSes may differ.

# Log

* [Directory update log](log.md)
