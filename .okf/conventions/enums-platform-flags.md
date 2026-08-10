---
type: Convention
title: Enums for platform flags
description: "FileControlFlag and FcntlCommand are int-backed with FULLY UPPERCASE cases (Linux/glibc values)."
resource: src/Enums/
tags: [convention, enums, posix, linux, glibc]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Enums note and FULLY UPPERCASE rule
  - id: file-control
    resource: src/Enums/FileControlFlag.php
    title: FileControlFlag enum
  - id: fcntl-cmd
    resource: src/Enums/FcntlCommand.php
    title: FcntlCommand enum
  - id: agents
    resource: AGENTS.md
    title: Enum case naming rule
---

# Why enums live here

`open(2)` / `fcntl(2)` flag and command integers are platform `#define`s. This package ships a small typed set for common **Linux/glibc** values so callers can avoid raw magic numbers for the usual cases.[^readme]

# Rules

- Use **int-backed** enums under `Microscrap\Bindings\POSIX\Enums\`.[^file-control][^fcntl-cmd]
- Case names are **FULLY UPPERCASE** (e.g. `FileControlFlag::O_RDWR`, `FcntlCommand::F_GETFL`).[^agents][^readme]
- No class-level constants in `src/` — prefer enums.[^agents]
- Pass `->value` (or a raw `int`) into helpers; helpers take integers, not enum objects.[^readme]

# Enum inventory (0.7.0)

| Enum | Purpose | Cases (summary) |
|------|---------|-----------------|
| `FileControlFlag` | `O_*` open / status flags | `O_RDONLY`, `O_WRONLY`, `O_RDWR`, `O_CREAT`, `O_TRUNC`, `O_NONBLOCK`, …[^file-control] |
| `FcntlCommand` | Linux `fcntl` commands | `F_GETFL` (= 3), `F_SETFL` (= 4)[^fcntl-cmd] |

Values are transcribed for Linux/glibc — they are **not** guaranteed identical on macOS / BSD. See [Platform flag values differ](../traps/platform-flag-values-differ.md).

# Related

* [1:1 extension wrap](one-to-one-extension-wrap.md)
* [Platform flag values differ](../traps/platform-flag-values-differ.md)

[^readme]: Enums note and FULLY UPPERCASE rule
[^file-control]: FileControlFlag enum
[^fcntl-cmd]: FcntlCommand enum
[^agents]: Enum case naming rule
