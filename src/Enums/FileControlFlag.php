<?php

namespace Microscrap\Bindings\POSIX\Enums;

enum FileControlFlag: int
{
    case O_RDONLY = 0;
    case O_WRONLY = 1;
    case O_RDWR = 2;
    case O_APPEND = 1024;
    case O_ASYNC = 8192;
    case O_CLOEXEC = 524288;
    case O_CREAT = 64;
    case O_DIRECT = 16384;
    case O_DIRECTORY = 65536;
    case O_DSYNC = 4096;
    case O_EXCL = 128;
    case O_NOATIME = 262144;
    case O_NOCTTY = 256;
    case O_NOFOLLOW = 131072;
    case O_NONBLOCK = 2048;
    case O_PATH = 2097152;
    case O_SYNC = 1052672;
    case O_TMPFILE = 4259840;
    case O_TRUNC = 512;
}
