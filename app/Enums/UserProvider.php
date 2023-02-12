<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserProvider extends Enum
{
    const GITHUB = 'github';
    const GOOGLE = 'google';
}
