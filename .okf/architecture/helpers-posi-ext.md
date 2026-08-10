---
type: Architecture
title: "Helpers → Posi\\* ext"
description: "Global helpers call Posi\\System (FD/syscalls) and Posi\\Memory (posi_mem_*); no static wrapper class."
resource: src/Helpers/posix-system.php
tags: [architecture, bindings, posix, helpers, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: helpers
    resource: src/Helpers/posix-system.php
    title: Helper file with Posi\\System and Posi\\Memory delegation
  - id: composer
    resource: composer.json
    title: Autoload files list
  - id: readme
    resource: README.md
    title: Helper API and Posi targets
  - id: agents
    resource: AGENTS.md
    title: Helpers call Posi\\System / Posi\\Memory only
---

# Call stack

```
app / peers / tests
    │
    ├─ posix_open / fcntl / ioctl / …     # global helpers (exact names)
    │       └─► Posi\System::*            # ext-posi
    │
    └─ posi_mem_alloc / posi_mem_read / …
            └─► Posi\Memory::*            # ext-posi
```

Rules:[^agents][^readme][^helpers]

1. Helpers call `Posi\System` or `Posi\Memory` only.
2. Do not invent a parallel PHP API surface or reimplement syscalls in this package.
3. There is **no** package-local static wrapper class (unlike `microscrap/open-gl`’s `GL`) — helpers are the public PHP surface.

# Autoload

Composer `autoload.files` registers:[^composer]

- `src/Helpers/posix-system.php`

Each function is wrapped in `if (! function_exists(...))` so a prior definition wins (including PHP’s built-in `posix` extension for overlapping names).[^helpers]

# Helper groups (0.7 surface)

| Group | Examples | Extension target |
|-------|----------|------------------|
| FD I/O | `posix_open`, `posix_close`, `posix_read`, `posix_write`, `posix_lseek`, `posix_readv`, `posix_recv` | `Posi\System` |
| Path / ownership | `posix_chmod`, `posix_chown`, `posix_fchmod`, `posix_fchown`, `posix_lstat`, `posix_umask` | `Posi\System` |
| Identity / host | `posix_getuid`, `posix_setuid`, `posix_hostname` | `Posi\System` |
| Control | `fcntl`, `ioctl` | `Posi\System` |
| Wait | `posix_wait`, `posix_waitpid` | `Posi\System` |
| Memory | `posi_mem_alloc`, `posi_mem_free`, `posi_mem_write`, `posi_mem_read` | `Posi\Memory` |

`fcntl` / `ioctl` write command-specific output into a **by-reference** `$value` after unpacking the extension’s return pair.[^helpers][^readme]

# Errors / style

- C-style return codes (`-1` / `false` on failure) — no exceptions from helpers in this package’s wrap.[^readme]
- Prefer `is_null($var)` over `$var === null` in package code.[^agents]

# Related

* [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md)
* [Enums for platform flags](../conventions/enums-platform-flags.md)
* [`function_exists` vs built-in posix](../traps/function-exists-builtin-posix.md)

[^helpers]: Helper file with Posi\System and Posi\Memory delegation
[^composer]: Autoload files list
[^readme]: Helper API and Posi targets
[^agents]: Helpers call Posi\System / Posi\Memory only
