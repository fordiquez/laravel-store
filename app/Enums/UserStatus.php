<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const BLOCKED = 'blocked';
}
