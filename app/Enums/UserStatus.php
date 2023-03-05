<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    public const ACTIVE = 'active';

    public const INACTIVE = 'inactive';

    public const BLOCKED = 'blocked';
}
