<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Handler\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\KlarnaPaymentBuilder;
use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\KlarnaPaymentBuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\AbstractPaymentMethod;

class KlarnaPaymentHandler extends AbstractPaymentHandler
{
    /**
     * @var KlarnaPaymentBuilder
     */
    private $klarnaPaymentBuilder;

    /**
     * KlarnaPaymentHandler constructor.
     *
     * @param string $validPaymentType
     * @param KlarnaPaymentBuilderInterface $klarnaPaymentBuilder
     */
    public function __construct(
        string $validPaymentType,
        KlarnaPaymentBuilderInterface $klarnaPaymentBuilder
    ) {
        parent::__construct($validPaymentType);
        $this->klarnaPaymentBuilder = $klarnaPaymentBuilder;
    }

     /**
     * @inheritDoc
     */
    public function serialize(AbstractPaymentMethod $payment): array
    {
        return $this->klarnaPaymentBuilder->createDataArray($payment);
    }

     /**
     * @inheritDoc
     */
    public function deserialize(array $data): AbstractPaymentMethod
    {
        return $this->klarnaPaymentBuilder->buildFromArray($data);
    }
}
