<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const SUPER_ADMIN = 'super_admin';

    const ADMIN = 'admin';

    const MANAGER = 'manager';

    const CUSTOMER = 'customer';
}
