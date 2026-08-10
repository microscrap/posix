# AGENTS.md — microscrap/posix

**Always read `.okf/index.md` first** before changing this package. Open only the concepts needed for the task; prefer `status: stable` when present. When you learn a durable package fact, update `.okf/` and append `.okf/log.md`.

## Role

Bindings-only Composer package over **ext-posi** (`php-io-extensions/posi`). Global helpers + enums. No ServiceProvider, no Chassis/Core coupling.

## Rules

* Helpers call `Posi\System` / `Posi\Memory` only — do not invent parallel APIs.
* Keep 1:1 coverage with helpers already in `src/Helpers/posix-system.php`; document drift in README / ecosystem docs.
* Enums in `src/Enums/*` are int-backed with **FULLY UPPERCASE** cases.
* Prefer `is_null($var)` over `$var === null`.
* No class-level constants; no Fabricate remaps in this package.

## Quick OKF map

| Need | Concept |
|------|---------|
| Identity / scope | `.okf/orientation/package.md` |
| Docs site | `.okf/orientation/ecosystem-docs.md` |
| Call stack | `.okf/architecture/helpers-posi-ext.md` |
| Enums | `.okf/conventions/enums-platform-flags.md` |
| Peer stack | `.okf/orientation/pairing-protocol-peers.md` |
| PHP `posix` clash | `.okf/traps/function-exists-builtin-posix.md` |
