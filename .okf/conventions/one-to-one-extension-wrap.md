---
type: Convention
title: "1:1 extension wrap"
description: "Helpers delegate to Posi\\System / Posi\\Memory only; keep coverage aligned with posix-system.php."
resource: src/
tags: [convention, bindings, posix, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: agents
    resource: AGENTS.md
    title: Agent wrap rules
  - id: readme
    resource: README.md
    title: Package README wrap description
  - id: helpers
    resource: src/Helpers/posix-system.php
    title: Helper delegation examples
---

# Rule

Match peer bindings packages (`microscrap/open-gl`, `microscrap/ftdi`, …) in spirit — thin wrap over the native extension:[^agents][^readme]

1. Global helpers use the names callers expect (`posix_open`, `fcntl`, `posi_mem_alloc`, …).
2. Helpers call **`Posi\System`** (FD / syscalls) or **`Posi\Memory`** (`posi_mem_*`) only — never invent parallel APIs.[^helpers][^agents]
3. Keep 1:1 coverage with helpers already in `src/Helpers/posix-system.php`; document drift in README / ecosystem docs.[^agents]
4. Platform `#define` flag integers live in backed enums — see [Enums for platform flags](enums-platform-flags.md).
5. No ServiceProvider, Chassis/Core coupling, or Fabricate remaps in this package.[^agents]
6. Prefer `is_null($var)` over `$var === null`; no class-level constants.[^agents]

# Architecture link

Full call-stack diagram: [Helpers → Posi\* ext](../architecture/helpers-posi-ext.md).

[^agents]: Agent wrap rules
[^readme]: Package README wrap description
[^helpers]: Helper delegation examples
