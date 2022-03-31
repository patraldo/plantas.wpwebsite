<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\Type\PaymentType;

final class KlarnaPayment extends AbstractPaymentMethod
{
    /**
     * KlarnaPayment constructor.
     *
     * @param string $uuid
     * @param float $amount
     */
    public function __construct(string $uuid, float $amount)
    {
        parent::__construct($uuid, $amount, PaymentType::klarnaPayment());
    }
}
