<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Handler\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\CardOnlinePaymentBuilder;
use Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment\CardOnlinePaymentBuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\AbstractPaymentMethod;

class CardOnlinePaymentHandler extends AbstractPaymentHandler
{
    /**
     * @var CardOnlinePaymentBuilder
     */
    private $cardOnlinePaymentBuilder;

    /**
     * CardOnlinePaymentHandler constructor.
     *
     * @param string $validPaymentType
     * @param CardOnlinePaymentBuilderInterface $cardOnlinePaymentBuilder
     */
    public function __construct(
        string $validPaymentType,
        CardOnlinePaymentBuilderInterface $cardOnlinePaymentBuilder
    ) {
        parent::__construct($validPaymentType);
        $this->cardOnlinePaymentBuilder = $cardOnlinePaymentBuilder;
    }

    /**
     * @inheritDoc
     */
    public function serialize(AbstractPaymentMethod $payment): array
    {
        return $this->cardOnlinePaymentBuilder->createDataArray($payment);
    }

    /**
     * @inheritDoc
     */
    public function deserialize(array $data): AbstractPaymentMethod
    {
        return $this->cardOnlinePaymentBuilder->buildFromArray($data);
    }
}
