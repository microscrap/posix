---
type: Orientation
title: Package (0.7)
description: "microscrap/posix 0.7.0 — POSIX PHP helpers over ext-posi; no ServiceProvider."
resource: .
tags: [orientation, posix, microscrap, bindings, 0.7]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package name, namespace, autoload helpers
  - id: readme
    resource: README.md
    title: Package README (0.7.x requirements and surface)
  - id: helpers
    resource: src/Helpers/posix-system.php
    title: Global helper autoload file
  - id: agents
    resource: AGENTS.md
    title: Agent rules for this package
---

# What it is

Composer package `microscrap/posix` at **0.7.0** — PHP helpers and enums over the [**php-io-extensions/posi**](https://github.com/php-io-extensions/posi) extension (`ext-posi`).[^readme][^composer]

| Field | Value |
|-------|-------|
| Name | `microscrap/posix`[^composer] |
| Version | `0.7.0`[^composer] |
| PHP | `^8.4\|^8.5\|^8.6`[^composer] |
| Namespace | `Microscrap\Bindings\POSIX\` → `src/`[^composer] |
| Require | `ext-posi` `^0.7.0`[^composer] |
| Suggest (peers) | `microscrap/uart`, `microscrap/gpio`, `microscrap/i2c`, `microscrap/spi` `^0.7`; `scrapyard-io/gpio-framework` `^0.7` |
| Homepage | Ecosystem docs overview (see [Ecosystem docs](ecosystem-docs.md))[^readme] |
| Discovery | **None** — no provider / Chassis registration in this package[^agents] |
| Role | Bindings layer only (global helpers + enums)[^agents][^readme] |

Autoloads `src/Helpers/posix-system.php` (each helper guarded with `function_exists`).[^composer][^helpers]

# What it is not

- Not `php-io-extensions/posi` (the native extension) — this package *wraps* that extension.[^readme]
- Not a ServiceProvider package — no Chassis / Core / Machine coupling; no Fabricate remaps.[^agents]
- Not a protocol driver — UART / GPIO / I2C / SPI live in peer packages that build on these FD helpers (see [Pair with protocol peers](pairing-protocol-peers.md)).
- Not PHP’s built-in `posix` extension — overlapping names may already be defined by that extension (see [`function_exists` vs built-in posix](../traps/function-exists-builtin-posix.md)).

# Public surface (summary)

| Layer | Location | Role |
|-------|----------|------|
| Helpers | `src/Helpers/posix-system.php` | Global FD / syscall helpers + `posi_mem_*` |
| Enums | `src/Enums/*` | Linux/glibc `O_*` / `F_*` flag integers as backed enums |
| Extension | `Posi\System`, `Posi\Memory` | Native API targets — not reimplemented here |

# Related

| Topic | Concept |
|-------|---------|
| Call stack | [Helpers → Posi\* ext](../architecture/helpers-posi-ext.md) |
| Wrap rules | [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md) |
| Enums | [Enums for platform flags](../conventions/enums-platform-flags.md) |
| Protocol peers | [Pair with protocol peers](pairing-protocol-peers.md) |
| Docs site | [Ecosystem docs](ecosystem-docs.md) |
| Extension | `php-io-extensions/posi` 0.7.0 |

[^composer]: Package name, namespace, autoload helpers
[^readme]: Package README (0.7.x requirements and surface)
[^helpers]: Global helper autoload file
[^agents]: Agent rules for this package
