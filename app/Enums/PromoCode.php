<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PromoCode extends Enum
{
    public const FIXED = 'fixed';

    public const PERCENTAGE = 'percentage';
}
