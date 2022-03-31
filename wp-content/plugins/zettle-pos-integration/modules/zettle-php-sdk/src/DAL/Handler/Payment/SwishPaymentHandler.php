<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Handler\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\SwishPaymentBuilder;
use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\SwishPaymentBuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\AbstractPaymentMethod;

class SwishPaymentHandler extends AbstractPaymentHandler
{
    /**
     * @var SwishPaymentBuilder
     */
    private $swishPaymentBuilder;

    /**
     * SwishPaymentHandler constructor.
     *
     * @param string $validPaymentType
     * @param SwishPaymentBuilderInterface $swishPaymentBuilder
     */
    public function __construct(
        string $validPaymentType,
        SwishPaymentBuilderInterface $swishPaymentBuilder
    ) {
        parent::__construct($validPaymentType);
        $this->swishPaymentBuilder = $swishPaymentBuilder;
    }

    /**
     * @inheritDoc
     */
    public function serialize(AbstractPaymentMethod $payment): array
    {
        return $this->swishPaymentBuilder->createDataArray($payment);
    }

    /**
     * @inheritDoc
     */
    public function deserialize(array $data): AbstractPaymentMethod
    {
        return $this->swishPaymentBuilder->buildFromArray($data);
    }
}
