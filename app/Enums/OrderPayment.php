<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderPayment extends Enum
{
    const CASH = 'cash';

    const STRIPE = 'stripe';

    const BANK_TRANSFER = 'bank_transfer';
}
