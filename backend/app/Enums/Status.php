<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: int
{
    case Pending = 0;
    case New = 1;
    case Processing = 2;
    case Completed = 3;
    case Canceled = 4;
}
