---
type: Orientation
title: Pair with protocol peers
description: "uart / gpio / i2c / spi build on this FD layer; ftdi/mpsse sit beside for USB; gpio-framework sits above."
resource: .
tags: [orientation, gpio, uart, i2c, spi, ftdi, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Bindings role and FD-centric helpers
  - id: agents
    resource: AGENTS.md
    title: Bindings-only role; no Chassis
  - id: package-orient
    resource: package.md
    title: Package orientation suggest peers
---

# Composition boundary

`microscrap/posix` is **bindings only** — FD open/read/write, `fcntl` / `ioctl`, and optional `posi_mem_*`. It does not implement UART framing, GPIO chip APIs, I2C/SPI transfers, or Chassis registration.[^readme][^agents]

| Concern | Package |
|---------|---------|
| POSIX FD / syscall helpers | `microscrap/posix` (this package) |
| UART | `microscrap/uart` `^0.7` |
| GPIO | `microscrap/gpio` `^0.7` |
| I2C | `microscrap/i2c` `^0.7` |
| SPI | `microscrap/spi` `^0.7` |
| USB MPSSE / FTDI | `microscrap/ftdi` (sits **beside** — USB path, not a posix child) |
| Higher GPIO orchestration | `scrapyard-io/gpio-framework` `^0.7` (above the microscrap protocol packages) |

# Typical flow

1. Depend on this package (and **ext-posi** `^0.7.0`).
2. Protocol peers open device nodes / descriptors via these helpers (or shared patterns) and speak their bus protocol.
3. Application / `gpio-framework` composes peers — do not invent framework providers inside this package.[^agents]

# Caveats

- Enum flag integers are Linux/glibc-oriented — see [Platform flag values differ](../traps/platform-flag-values-differ.md).
- Overlapping helper names may be claimed by PHP’s built-in `posix` extension — see [`function_exists` vs built-in posix](../traps/function-exists-builtin-posix.md).

# Related

* [Package (0.7)](package.md)
* [Helpers → Posi\* ext](../architecture/helpers-posi-ext.md)

[^readme]: Bindings role and FD-centric helpers
[^agents]: Bindings-only role; no Chassis
