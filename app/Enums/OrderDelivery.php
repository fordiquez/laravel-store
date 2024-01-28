<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderDelivery extends Enum
{
    public const COURIER = 'courier';

    public const MEEST = 'meest';

    public const UKRPOSHTA = 'ukrposhta';

    public const NOVA_POSHTA = 'nova_poshta';
}
