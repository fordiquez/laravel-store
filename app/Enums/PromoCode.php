<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PromoCode extends Enum
{
    const FIXED = 'fixed';

    const PERCENTAGE = 'percentage';
}
