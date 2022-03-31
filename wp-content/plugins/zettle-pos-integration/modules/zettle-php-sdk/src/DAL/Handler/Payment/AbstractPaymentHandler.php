<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Handler\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\AbstractPaymentMethod;

abstract class AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @var string
     */
    private $validPaymentType;

    /**
     * AbstractPaymentHandler constructor.
     *
     * @param string $validPaymentType
     */
    public function __construct(string $validPaymentType)
    {
        $this->validPaymentType = $validPaymentType;
    }

    /**
     * @inheritDoc
     */
    public function accepts(string $paymentType): bool
    {
        return $paymentType === $this->validPaymentType;
    }

    /**
     * @inheritDoc
     */
    abstract public function serialize(AbstractPaymentMethod $payment): array;

    /**
     * @inheritDoc
     */
    abstract public function deserialize(array $data): AbstractPaymentMethod;
}
