<?php

namespace App\Enums;

use App\Enums\Traits\EnumToCollection;

enum PatientStatusEnum: int
{
    use EnumToCollection;

    case CHECKING = 1;

    case CHECKED = 2;
}
