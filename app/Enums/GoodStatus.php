<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GoodStatus extends Enum
{
    public const READY_FOR_DISPATCH = 'ready for dispatch';

    public const IN_STOCK = 'in stock';

    public const ENDS = 'ends';

    public const IS_OVER = 'is over';

    public const OUT_OF_STOCK = 'out of stock';

    public const DISCONTINUED = 'discontinued';
}
