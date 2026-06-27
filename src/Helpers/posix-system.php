<?php

use Posi\System;

if(!function_exists('posix_close'))
{
    function posix_close(int $fd): int
    {
        return System::close($fd);
    }
}

if(!function_exists('fcntl'))
{
    function fcntl(int $fd, int $cmd, mixed $arg, mixed &$value): int
    {
        [$ret, $val] = array_values(System::fcntl($fd, $cmd, $arg));
        $value = $val;
        return $ret;
    }
}

if(!function_exists('ioctl'))
{
    function ioctl(int $fd, int $cmd, mixed $arg, mixed &$value): int
    {
        [$ret, $val] = array_values(System::ioctl($fd, $cmd, $arg));
        $value = $val;
        return $ret;
    }
}

if(!function_exists('posix_open'))
{
    function posix_open(string $device_path, int $flags = 2, int $mode = 0644): int
    {
        return System::open($device_path, $flags, $mode);
    }
}

if(!function_exists('posix_read'))
{
    function posix_read(int $fd, int $bytes_to_read): string|false
    {
        return System::read($fd, $bytes_to_read);
    }
}

if(!function_exists('posix_write'))
{
    function posix_write(int $fd, string $data, int $bytes_to_write): int
    {
        return System::write($fd, $data, $bytes_to_write);
    }
}

if(!function_exists('posix_chmod'))
{
    function posix_chmod(string $path, int $mode): int
    {
        return System::chmod($path, $mode);
    }
}

if(!function_exists('posix_chown'))
{
    function posix_chown(string $path, int $owner, int $group): int
    {
        return System::chown($path, $owner, $group);
    }
}

if(!function_exists('posix_fchmod'))
{
    function posix_fchmod(int $fd, int $mode): int
    {
        return System::fchmod($fd, $mode);
    }
}

if(!function_exists('posix_fchown'))
{
    function posix_fchown(int $fd, int $owner, int $group): int
    {
        return System::fchown($fd, $owner, $group);
    }
}

if(!function_exists('posix_getuid'))
{
    function posix_getuid(): int
    {
        return System::getuid();
    }
}

if(!function_exists('posix_setuid'))
{
    function posix_setuid(int $uid): int
    {
        return System::setuid($uid);
    }
}

if(!function_exists('posix_umask'))
{
    function posix_umask(int $mask): int
    {
        return System::umask($mask);
    }
}

if(!function_exists('posix_lseek'))
{
    function posix_lseek(int $fd, int $offset, int $whence): int
    {
        return System::lseek($fd, $offset, $whence);
    }
}

if(!function_exists('posix_readv'))
{
    function posix_readv(int $fd, array $iovecs): array|false
    {
        return System::readv($fd, $iovecs);
    }
}

if(!function_exists('posix_recv'))
{
    function posix_recv(int $fd, int $len, int $flags = 0): string|false
    {
        return System::recv($fd, $len, $flags);
    }
}

if(!function_exists('posix_wait'))
{
    function posix_wait(?int &$status = null): int
    {
        return System::wait($status);
    }
}

if(!function_exists('posix_waitpid'))
{
    function posix_waitpid(int $pid, ?int &$status = null, int $options = 0): int
    {
        return System::waitpid($pid, $status, $options);
    }
}

if(!function_exists('posix_hostname'))
{
    function posix_hostname(): string|false
    {
        return System::hostname();
    }
}

if(!function_exists('posix_lstat'))
{
    function posix_lstat(string $path): array|false
    {
        return System::lstat($path);
    }
}

if (!function_exists('posi_mem_alloc')) {
    function posi_mem_alloc(int $size): int
    {
        return \Posi\Memory::alloc($size);
    }
}

if (!function_exists('posi_mem_free')) {
    function posi_mem_free(int $ptr): void
    {
        \Posi\Memory::free($ptr);
    }
}

if (!function_exists('posi_mem_write')) {
    function posi_mem_write(int $ptr, string $data, int $offset = 0): void
    {
        \Posi\Memory::write($ptr, $data, $offset);
    }
}

if (!function_exists('posi_mem_read')) {
    function posi_mem_read(int $ptr, int $size, int $offset = 0): string
    {
        return \Posi\Memory::read($ptr, $size, $offset);
    }
}
