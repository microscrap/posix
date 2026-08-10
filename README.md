# microscrap/posix — POSIX bindings for ScrapyardIO

> **Docs (production):** [ScrapyardIO · microscrap/posix 0.7.x](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/posix/0.7.x/overview)

[![Docs](https://img.shields.io/badge/docs-ScrapyardIO-0ea5e9?logo=readthedocs&logoColor=white)](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/posix/0.7.x/overview)
[![Packagist Version](https://img.shields.io/packagist/v/microscrap/posix.svg?label=packagist)](https://packagist.org/packages/microscrap/posix)
[![PHP Version Require](https://img.shields.io/packagist/php-v/microscrap/posix.svg)](https://packagist.org/packages/microscrap/posix)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Requires ext-posi](https://img.shields.io/badge/ext--posi-%5E0.7-777bb4?logo=php&logoColor=white)](https://github.com/php-io-extensions/posi)

PHP library that wraps the [**posi**](https://github.com/php-io-extensions/posi) extension (`ext-posi`) with global helpers and enums. Every helper delegates to `Posi\System` (or `Posi\Memory` for `posi_mem_*`).

This is the **bindings** package — not the native extension. Ecosystem docs: [`0.7.x`](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/posix/0.7.x/overview).

## Highlights

* Retrieve file descriptors to replace `fopen`
* Read and write to file descriptors beyond streams, replacing `fread` and `fwrite`
* Direct access to `fcntl` and `ioctl`
* Optional `posi_mem_*` helpers over `Posi\Memory`
* Typed enums for common Linux `O_*` / `F_*` flag values

## Requirements

* PHP `^8.4|^8.5|^8.6`
* **ext-posi** `^0.7.0` — install from [php-io-extensions/posi](https://github.com/php-io-extensions/posi)

## Installation

Confirm **ext-posi** is loaded before using this library:

```bash
php -m | grep posi
```

```bash
composer require microscrap/posix
```

Composer autoloads `src/Helpers/posix-system.php`, registering the global helpers when the package is installed.

## Usage

POSIX I/O is invoked through **global helper functions** (for example `posix_open`, `posix_read`). Helpers are only defined if the name is not already taken (`function_exists` guard).

Optional enums live under `Microscrap\Bindings\POSIX\Enums` (`FileControlFlag`, `FcntlCommand`) — cases are **FULLY UPPERCASE**.

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/null', FileControlFlag::O_RDWR->value);
posix_close($fd);
```

`posix_open()`, `fcntl()`, and `ioctl()` take the same flag and command integers as C (`O_RDONLY`, `F_GETFL`, and so on). Prefer the shipped enums for common Linux/glibc values, or define platform constants from your OS headers.

---

## Global Helper API

### `posix_open(string $filepath, int $flags = 2, int $mode = 0644): int`

Opens a path and returns a **file descriptor** (non-negative integer), or `-1` on failure (same semantics as C `open(2)`).

* `$filepath` — device node, pipe, socket path, or regular file path.
* `$flags` — `O_*` access mode and creation flags (e.g. `O_RDWR`, `O_CREAT | O_TRUNC`). Default `2` (`O_RDWR` on Linux/glibc).
* `$mode` — permission bits when `O_CREAT` is set (default `0644`).

**Example — open an existing file for reading**

```php
<?php

// O_RDONLY is platform-specific; 0 on Linux/glibc.
$fd = posix_open('/etc/hosts', 0);

if ($fd < 0) {
    throw new RuntimeException('open failed');
}

$chunk = posix_read($fd, 256);
posix_close($fd);
```

**Example — create and truncate a file for read/write**

```php
<?php

// Linux/glibc: O_RDWR | O_CREAT | O_TRUNC
$flags = 2 | 64 | 512;

$fd = posix_open('/tmp/posix-demo.txt', $flags, 0644);

if ($fd < 0) {
    throw new RuntimeException('open failed');
}

posix_write($fd, "hello\n", 6);
posix_close($fd);
```

---

### `posix_close(int $fd): int`

Closes a file descriptor. Returns `0` on success and `-1` on failure (same semantics as C `close(2)`).

**Example**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/null', FileControlFlag::O_RDWR->value);

$result = posix_close($fd);
// $result === 0 on success
```

---

### `posix_chmod(string $path, int $mode): int`

Sets permission bits on a path. Returns `0` on success and `-1` on failure (same semantics as C `chmod(2)`).

* `$path` — file or directory path.
* `$mode` — permission bits (e.g. `0644`). Combine with `S_ISUID`, `S_ISGID`, and `S_ISVTX` where your platform supports them.

Changing modes on paths you do not own, or setting set-user-ID bits, typically requires appropriate privileges.

**Example**

```php
<?php

$path = '/tmp/posix-chmod-demo.txt';
$fd = posix_open($path, 2 | 64 | 512, 0644);
posix_close($fd);

if (posix_chmod($path, 0600) !== 0) {
    throw new RuntimeException('chmod failed');
}
```

---

### `posix_chown(string $path, int $owner, int $group): int`

Sets owner and group for a path. Returns `0` on success and `-1` on failure (same semantics as C `chown(2)`).

* `$owner` — numeric user ID (`uid_t`).
* `$group` — numeric group ID (`gid_t`).

This call usually requires superuser privileges or capability `CAP_CHOWN` on Linux.

**Example**

```php
<?php

$path = '/tmp/owned-by-me.txt';
$fd = posix_open($path, 2 | 64 | 512, 0644);
posix_close($fd);

$uid = (int) posix_getuid();
$gid = (int) posix_getgid();

if (posix_chown($path, $uid, $gid) !== 0) {
    throw new RuntimeException('chown failed');
}
```

---

### `posix_fchmod(int $fd, int $mode): int`

Like `posix_chmod`, but applies to an open file descriptor. Returns `0` on success and `-1` on failure (same semantics as C `fchmod(2)`).

**Example**

```php
<?php

$fd = posix_open('/tmp/posix-fchmod.txt', 2 | 64 | 512, 0644);

if (posix_fchmod($fd, 0600) !== 0) {
    posix_close($fd);
    throw new RuntimeException('fchmod failed');
}

posix_close($fd);
```

---

### `posix_fchown(int $fd, int $owner, int $group): int`

Like `posix_chown`, but applies to the file referred to by an open descriptor. Returns `0` on success and `-1` on failure (same semantics as C `fchown(2)`).

**Example**

```php
<?php

$fd = posix_open('/tmp/posix-fchown.txt', 2 | 64 | 512, 0644);

$uid = (int) posix_getuid();
$gid = (int) posix_getgid();

if (posix_fchown($fd, $uid, $gid) !== 0) {
    posix_close($fd);
    throw new RuntimeException('fchown failed');
}

posix_close($fd);
```

---

### `posix_write(int $fd, string $data, int $bytes_to_write): int`

Writes up to `$bytes_to_write` bytes from `$data` to the descriptor. Returns the number of bytes written, or `-1` on failure (same semantics as C `write(2)`).

**Example — write a line to a file descriptor**

```php
<?php

$fd = posix_open('/tmp/posix-demo.txt', 2 | 64 | 512, 0644);

$payload = "ping\n";
$written = posix_write($fd, $payload, strlen($payload));

// $written === 5 when all bytes were accepted
posix_close($fd);
```

**Example — write to `/dev/null`**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/null', FileControlFlag::O_WRONLY->value);

posix_write($fd, str_repeat('x', 1024), 1024);
posix_close($fd);
```

---

### `posix_read(int $fd, int $bytes_to_read): string|false`

Reads up to `$bytes_to_read` bytes from the descriptor. Returns a **binary string** of the bytes read (length may be less than requested). Returns `false` if the underlying `read(2)` fails.

**Example — read the first 512 bytes of a file**

```php
<?php

$fd = posix_open('/etc/hosts', 0);

$data = posix_read($fd, 512);

if ($data === false) {
    throw new RuntimeException('read failed');
}

echo $data;
posix_close($fd);
```

**Example — read from a pipe in a loop**

```php
<?php

$fd = /* descriptor from pipe, socket, or device */;

while (true) {
    $chunk = posix_read($fd, 4096);

    if ($chunk === false) {
        break;
    }

    if ($chunk === '') {
        break; // EOF
    }

    // process $chunk
}
```

---

### `posix_getuid(): int`

Returns the real user ID of the calling process (same semantics as C `getuid(2)`).

This helper is registered only when `posix_getuid()` is not already defined (for example by PHP’s built-in `posix` extension). When the built-in exists, PHP’s version is used instead.

**Example**

```php
<?php

$uid = posix_getuid();
```

---

### `posix_setuid(int $uid): int`

Sets the real user ID of the calling process. Returns `0` on success and `-1` on failure (same semantics as C `setuid(2)`). Changing UID typically requires appropriate privileges.

Registered only when `posix_setuid()` is not already defined.

**Example**

```php
<?php

if (posix_setuid(65534) !== 0) {
    // not privileged or operation denied
}
```

---

### `posix_umask(int $mask): int`

Sets the file mode creation mask. Returns the **previous** umask value (same semantics as C `umask(2)`). Values are numeric (for example octal `022` is the integer `18` on typical platforms).

**Example**

```php
<?php

$previous = posix_umask(0077);
// restore prior mask
posix_umask($previous);
```

---

### `posix_lseek(int $fd, int $offset, int $whence): int`

Repositions the offset of the open descriptor. Returns the new offset measured in bytes from the beginning of the file, or `-1` on failure (same semantics as C `lseek(2)`). Use `SEEK_SET`, `SEEK_CUR`, and `SEEK_END` from your platform.

**Example**

```php
<?php

$fd = posix_open('/etc/hosts', 0);

$pos = posix_lseek($fd, 0, SEEK_SET);
posix_close($fd);
```

---

### `posix_recv(int $fd, int $len, int $flags = 0): string|false`

Receives up to `$len` bytes from a **socket** descriptor (same semantics as C `recv(2)`). Returns a binary string (possibly shorter than `$len`), or `false` on failure. Optional `$flags` are the usual `MSG_*` constants for your platform (`0` to block until data is available).

**Example**

```php
<?php

// $sock must be a connected socket FD.
// $data = posix_recv($sock, 4096, 0);
```

---

### `posix_readv(int $fd, array $iovecs): array|false`

Scatter-read into several buffers in one syscall (`readv(2)`). Each element of `$iovecs` is an associative array:

| Key    | Meaning |
|--------|---------|
| `len`  | Required. Maximum bytes to place in this segment. |
| `base` | Optional. If present as a string, up to `min(strlen($base), len)` bytes are copied into the segment before the read (usually you omit this for a fresh read buffer). |

On success, returns:

| Key       | Meaning |
|-----------|---------|
| `res`     | Total bytes read (non-negative), or `0` at EOF. |
| `buffers` | List of binary strings, one per `$iovecs` entry, each truncated to the portion of the read that landed in that segment. |

Returns `false` if the syscall fails or if `$iovecs` is invalid.

**Example — two segments from a regular file**

```php
<?php

$fd = posix_open('/etc/hosts', 0);

$out = posix_readv($fd, [
    ['len' => 4],
    ['len' => 8],
]);

if ($out !== false) {
    // $out['res'] — total bytes read
    // $out['buffers'][0], $out['buffers'][1] — segment contents
}

posix_close($fd);
```

---

### `fcntl(int $fd, int $command, mixed $arg, mixed &$value): int`

Invokes `fcntl(2)` on the descriptor. Returns the syscall status (`0` or non-negative on success, `-1` on failure). Command-specific output is written to `$value` **by reference** (flag integer for getters like `F_GETFL`, `flock` array for lock commands, or echo of the argument for setters).

`$arg` may be an integer, a boolean, or (for `F_GETLK` / `F_SETLK` / `F_SETLKW`) an array with keys: `type`, `whence`, `start`, `len`, `pid`.

**Example — read open-file status flags (`F_GETFL`)**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FcntlCommand;

$fd = posix_open('/tmp/posix-demo.txt', 0);

$value = 0;
$res = fcntl($fd, FcntlCommand::F_GETFL->value, 0, $value);

// $res — fcntl status
// $value — current O_* flags as integer
$flags = $value;

posix_close($fd);
```

**Example — enable non-blocking I/O (`F_SETFL`)**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FcntlCommand;
use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/tmp/posix-demo.txt', 0);

$current = 0;
fcntl($fd, FcntlCommand::F_GETFL->value, 0, $current);
$newFlags = $current | FileControlFlag::O_NONBLOCK->value;

$ignored = 0;
fcntl($fd, FcntlCommand::F_SETFL->value, $newFlags, $ignored);
posix_close($fd);
```

**Example — advisory lock (`F_SETLK`)**

```php
<?php

$fd = posix_open('/tmp/posix-demo.txt', 2);

$value = 0;
$res = fcntl($fd, F_SETLK, [
    'type'   => F_WRLCK,
    'whence' => SEEK_SET,
    'start'  => 0,
    'len'    => 0,
    'pid'    => 0,
], $value);

// $value contains flock fields after the operation
posix_close($fd);
```

---

### `ioctl(int $fd, int $command, mixed $arg, mixed &$value): int`

Invokes `ioctl(2)` on the descriptor. Returns the syscall status. Output is written to `$value` **by reference**: integer, string buffer, or argument echo depending on command and `$arg`.

`$arg` may be an integer pointer value, a string buffer, or an array:

* `['value' => int]` — read/write a single integer through the ioctl (value updated in `$value`).
* `['bytes' => string]` or `['data' => string]` — binary buffer in/out.

**Example — ioctl with no argument (device-specific command)**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/null', FileControlFlag::O_RDWR->value);

$value = 0;
$res = ioctl($fd, SOME_IOCTL_CMD, null, $value);

posix_close($fd);
```

**Example — pass an integer argument**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/some-device', FileControlFlag::O_RDWR->value);

$value = 0;
$res = ioctl($fd, SOME_IOCTL_CMD, 0, $value);

posix_close($fd);
```

**Example — integer in/out via array**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/some-device', FileControlFlag::O_RDWR->value);

$value = 0;
$res = ioctl($fd, SOME_IOCTL_CMD, ['value' => 1], $value);

// $value holds the updated integer after ioctl
$out = $value;

posix_close($fd);
```

**Example — binary buffer in/out**

```php
<?php

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

$fd = posix_open('/dev/some-device', FileControlFlag::O_RDWR->value);

$buffer = str_repeat("\0", 32);
$value = 0;

$res = ioctl($fd, SOME_IOCTL_CMD, ['data' => $buffer], $value);

// $value is the buffer returned from the driver
$response = $value;

posix_close($fd);
```

---

### `posix_wait(?int &$status = null): int`

Blocks until any child process exits (implemented with `waitpid(-1, …, 0)`, same idea as `wait(2)`). Returns the child PID on success, or `-1` on error (for example `ECHILD` when there are no children). If you pass `$status` **by reference**, it receives the raw wait status integer for use with `pcntl_wifexited()`, `pcntl_wexitstatus()`, and related helpers (or your own bit tests). If the call fails, `$status` is not updated.

**Example**

```php
<?php

$pid = pcntl_fork();
if ($pid === 0) {
    exit(42);
}

$status = 0;
$got = posix_wait($status);
// decode with pcntl_wexitstatus($status) when pcntl_wifexited($status)
```

---

### `posix_waitpid(int $pid, ?int &$status = null, int $options = 0): int`

Wraps `waitpid(2)`. Use `$pid = -1` to wait for any child (same as `posix_wait()`). `$options` is typically `0` (block) or `WNOHANG` (do not block). Returns the child PID on success when a child was reaped, `0` when `$options` includes `WNOHANG` and no child was ready, or `-1` on error. The raw status word is written to `$status` **by reference** only when the return value is greater than zero (a child was reaped).

**Example**

```php
<?php

$status = 0;
$pid = posix_waitpid(-1, $status, WNOHANG);
```

---

### `posix_hostname(): string|false`

Returns the current host name via `gethostname(2)`. Returns `false` if the syscall fails.

Registered only when `posix_hostname()` is not already defined.

**Example**

```php
<?php

$host = posix_hostname();
```

---

### `posix_lstat(string $path): array|false`

Returns file status information via `lstat(2)`. Unlike `stat(2)`, `lstat` does not follow symbolic links — it reports metadata for the link itself.

On success, returns an associative array with the same keys as PHP’s built-in `lstat()`:

| Key       | Meaning |
|-----------|---------|
| `dev`     | Device number of the filesystem containing the file. |
| `ino`     | Inode number. |
| `mode`    | File type and permissions (`st_mode`). |
| `nlink`   | Number of hard links. |
| `uid`     | Owner user ID. |
| `gid`     | Owner group ID. |
| `rdev`    | Device type (for special files). |
| `size`    | Total size in bytes. |
| `blksize` | Preferred I/O block size. |
| `blocks`  | Number of 512-byte blocks allocated. |
| `atime`   | Last access time (Unix timestamp). |
| `mtime`   | Last modification time (Unix timestamp). |
| `ctime`   | Last inode change time (Unix timestamp). |

Returns `false` if the syscall fails.

**Example**

```php
<?php

$info = posix_lstat('/tmp/my-symlink');

if ($info !== false) {
    // $info['size'], $info['mode'], etc.
}
```

---

### `posi_mem_alloc(int $size): int`

Allocates `$size` bytes via `Posi\Memory::alloc` and returns a pointer integer usable with the other `posi_mem_*` helpers.

### `posi_mem_free(int $ptr): void`

Frees a pointer previously returned by `posi_mem_alloc`.

### `posi_mem_write(int $ptr, string $data, int $offset = 0): void`

Writes `$data` into the allocated buffer at `$offset`.

### `posi_mem_read(int $ptr, int $size, int $offset = 0): string`

Reads `$size` bytes from the allocated buffer at `$offset`.

**Example**

```php
<?php

$ptr = posi_mem_alloc(16);
posi_mem_write($ptr, "hello\0");
$chunk = posi_mem_read($ptr, 5);
posi_mem_free($ptr);
```

---

## Quick reference

| Helper | Signature |
|--------|-----------|
| `posix_open` | `posix_open(string $filepath, int $flags = 2, int $mode = 0644): int` |
| `posix_chmod` | `posix_chmod(string $path, int $mode): int` |
| `posix_chown` | `posix_chown(string $path, int $owner, int $group): int` |
| `posix_fchmod` | `posix_fchmod(int $fd, int $mode): int` |
| `posix_fchown` | `posix_fchown(int $fd, int $owner, int $group): int` |
| `posix_close` | `posix_close(int $fd): int` |
| `posix_write` | `posix_write(int $fd, string $data, int $bytes_to_write): int` |
| `posix_read` | `posix_read(int $fd, int $bytes_to_read): string\|false` |
| `posix_getuid` | `posix_getuid(): int` |
| `posix_setuid` | `posix_setuid(int $uid): int` |
| `posix_umask` | `posix_umask(int $mask): int` |
| `posix_lseek` | `posix_lseek(int $fd, int $offset, int $whence): int` |
| `posix_recv` | `posix_recv(int $fd, int $len, int $flags = 0): string\|false` |
| `posix_readv` | `posix_readv(int $fd, array $iovecs): array\|false` |
| `fcntl` | `fcntl(int $fd, int $command, mixed $arg, mixed &$value): int` |
| `ioctl` | `ioctl(int $fd, int $command, mixed $arg, mixed &$value): int` |
| `posix_wait` | `posix_wait(?int &$status = null): int` |
| `posix_waitpid` | `posix_waitpid(int $pid, ?int &$status = null, int $options = 0): int` |
| `posix_hostname` | `posix_hostname(): string\|false` |
| `posix_lstat` | `posix_lstat(string $path): array\|false` |
| `posi_mem_alloc` | `posi_mem_alloc(int $size): int` |
| `posi_mem_free` | `posi_mem_free(int $ptr): void` |
| `posi_mem_write` | `posi_mem_write(int $ptr, string $data, int $offset = 0): void` |
| `posi_mem_read` | `posi_mem_read(int $ptr, int $size, int $offset = 0): string` |

## License

MIT. See [LICENSE](LICENSE).
