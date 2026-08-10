---
type: Trap
title: "`function_exists` vs built-in posix"
description: "Helpers skip definition when the name exists; PHP’s built-in posix extension may win for overlapping names."
resource: src/Helpers/posix-system.php
tags: [trap, autoload, posix, helpers, php-ext]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:22:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README notes on function_exists and posix_getuid
  - id: helpers
    resource: src/Helpers/posix-system.php
    title: function_exists guard pattern
  - id: agents
    resource: AGENTS.md
    title: Bindings-over-ext-posi role
---

# Symptom

A call such as `posix_getuid()` (or another overlapping `posix_*` name) runs, but behavior matches PHP’s built-in **posix** extension — not the `Posi\System` wrap from this package / **ext-posi**.

# Cause

Every helper is defined only when the name is free:[^helpers]

```php
if (! function_exists('posix_getuid')) {
    function posix_getuid(): int
    {
        return System::getuid();
    }
}
```

PHP’s built-in `posix` extension already defines a subset of these names. Under the guard, **the first definition wins** — typically the built-in when that extension is loaded.[^readme]

Names unique to this wrap (for example `posix_open`, `fcntl` / `ioctl` as provided here, `posi_mem_*`) are unaffected unless something else registered them first.

# Mitigation

- Confirm whether `ext-posix` (built-in) is loaded when diagnosing unexpected behavior.
- Prefer helpers that are unique to **ext-posi** / this package for FD work (`posix_open`, `posix_read`, …).
- For overlapping identity helpers, treat the built-in as authoritative when it won the race, or disable / avoid loading the conflicting extension in constrained environments if you need the Posi path.

# Related

* [Helpers → Posi\* ext](../architecture/helpers-posi-ext.md)
* [Package (0.7)](../orientation/package.md)

[^readme]: README notes on function_exists and posix_getuid
[^helpers]: function_exists guard pattern
[^agents]: Bindings-over-ext-posi role
