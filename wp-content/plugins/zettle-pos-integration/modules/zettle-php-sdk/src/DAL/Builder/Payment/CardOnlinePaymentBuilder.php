<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\CardOnlinePayment;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\PaymentFactory;
use Inpsyde\Zettle\PhpSdk\DAL\Exception\EntityFactoryException;

class CardOnlinePaymentBuilder implements CardOnlinePaymentBuilderInterface
{
    /**
     * @var PaymentFactory
     */
    private $paymentFactory;

    /**
     * CardOnlinePaymentBuilder constructor.
     *
     * @param PaymentFactory $paymentFactory
     */
    public function __construct(PaymentFactory $paymentFactory)
    {
        $this->paymentFactory = $paymentFactory;
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(CardOnlinePayment $cardOnlinePayment): array
    {
        return [
            'uuid' => (string) $cardOnlinePayment->uuid(),
            'amount' => $cardOnlinePayment->amount(),
            'type' => $cardOnlinePayment->type()->getValue(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): CardOnlinePayment
    {
        return $this->build($data);
    }

    /**
     * @param array $data
     *
     * @return CardOnlinePayment
     *
     * @throws EntityFactoryException
     */
    private function build(array $data): CardOnlinePayment
    {
        return $this->paymentFactory->createCardOnlinePayment(
            $data['uuid'],
            $data['amount'],
            $data['type']
        );
    }
}
