<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\Type\PaymentType;

abstract class AbstractPaymentMethod
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var PaymentType
     */
    private $type;

    /**
     * AbstractPaymentMethod constructor.
     *
     * @param string $uuid
     * @param float $amount
     * @param PaymentType $type
     */
    public function __construct(string $uuid, float $amount, PaymentType $type)
    {
        $this->uuid = $uuid;
        $this->amount = $amount;
        $this->type = $type;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function type(): PaymentType
    {
        return $this->type;
    }
}
