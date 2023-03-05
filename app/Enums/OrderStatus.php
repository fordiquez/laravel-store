<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    public const UNPAID = 'unpaid';

    public const PAID = 'paid';

    public const UNDER_PROCESS = 'under_process';

    public const PROCESSING = 'processing';

    public const FINISHED = 'finished';

    public const REJECTED = 'rejected';

    public const CANCELED = 'canceled';

    public const REFUNDED_REQUEST = 'refunded_request';

    public const REFUNDED = 'refunded';

    public const RETURNED = 'returned';
}
