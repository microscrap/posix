<?php

namespace Microscrap\Bindings\POSIX\Enums;

/** Linux `fcntl` commands (fcntl.h). */
enum FcntlCommand: int
{
    case F_GETFL = 3;

    case F_SETFL = 4;
}
