<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Payment;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\PaymentFactory;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Payment\SwishPayment;
use Inpsyde\Zettle\PhpSdk\DAL\Exception\EntityFactoryException;

class SwishPaymentBuilder implements SwishPaymentBuilderInterface
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
    public function createDataArray(SwishPayment $swishPayment): array
    {
        return [
            'uuid' => (string) $swishPayment->uuid(),
            'amount' => $swishPayment->amount(),
            'type' => $swishPayment->type()->getValue(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): SwishPayment
    {
        return $this->build($data);
    }

    /**
     * @param array $data
     *
     * @return SwishPayment
     *
     * @throws EntityFactoryException
     */
    private function build(array $data): SwishPayment
    {
        return $this->paymentFactory->createSwishPayment(
            $data['uuid'],
            $data['type'],
            $data['amount']
        );
    }
}
