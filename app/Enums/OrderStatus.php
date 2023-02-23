<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    const UNPAID = 'unpaid';

    const PAID = 'paid';

    const UNDER_PROCESS = 'under_process';

    const PROCESSING = 'processing';

    const FINISHED = 'finished';

    const REJECTED = 'rejected';

    const CANCELED = 'canceled';

    const REFUNDED_REQUEST = 'refunded_request';

    const REFUNDED = 'refunded';

    const RETURNED = 'returned';
}
