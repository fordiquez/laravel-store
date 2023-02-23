<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderDelivery extends Enum
{
    const COURIER = 'courier';

    const MEEST = 'meest';

    const UKRPOSHTA = 'ukrposhta';

    const NOVA_POSHTA = 'nova_poshta';
}
