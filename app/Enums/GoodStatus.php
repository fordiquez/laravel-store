<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GoodStatus extends Enum
{
    const READY_FOR_DISPATCH = 'ready for dispatch';

    const IN_STOCK = 'in stock';

    const ENDS = 'ends';

    const IS_OVER = 'is over';

    const OUT_OF_STOCK = 'out of stock';

    const DISCONTINUED = 'discontinued';
}
