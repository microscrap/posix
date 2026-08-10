---
type: Trap
title: Platform flag values differ
description: "FileControlFlag / FcntlCommand integers match Linux/glibc; macOS and BSD may use different numeric values."
resource: src/Enums/
tags: [trap, enums, linux, glibc, portability]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README note on platform-specific O_* / F_* values
  - id: file-control
    resource: src/Enums/FileControlFlag.php
    title: Linux/glibc O_* values
  - id: fcntl-cmd
    resource: src/Enums/FcntlCommand.php
    title: Linux fcntl.h command values
---

# Symptom

`posix_open(..., FileControlFlag::O_CREAT->value, …)` or `fcntl(..., FcntlCommand::F_GETFL->value, …)` misbehaves, opens with unexpected flags, or returns wrong status bits on a non-Linux host.

# Cause

Shipped enum case values are transcribed from **Linux/glibc** headers (for example `O_RDWR = 2`, `O_CREAT = 64`, `F_GETFL = 3`).[^file-control][^fcntl-cmd] Other Unixes (macOS, FreeBSD, …) often use **different integers** for the same symbolic names.[^readme]

The helpers pass integers straight through to **ext-posi**; they do not remap flags per OS.

# Mitigation

- On Linux/glibc targets, prefer the shipped enums.
- On other platforms, define flag/command integers from that OS’s headers (or platform-specific constants) instead of assuming these enum values.[^readme]
- Do not treat enum case *names* as proof that the *numeric* value is portable.

# Related

* [Enums for platform flags](../conventions/enums-platform-flags.md)
* [Helpers → Posi\* ext](../architecture/helpers-posi-ext.md)

[^readme]: README note on platform-specific O_* / F_* values
[^file-control]: Linux/glibc O_* values
[^fcntl-cmd]: Linux fcntl.h command values
