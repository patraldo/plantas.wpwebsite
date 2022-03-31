<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Balance;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\Balance;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\BalanceFactory;

class BalanceBuilder implements BalanceBuilderInterface
{
    /**
     * @var BalanceFactory
     */
    private $balanceFactory;

    /**
     * BalanceBuilder constructor.
     *
     * @param BalanceFactory $balanceFactory
     */
    public function __construct(BalanceFactory $balanceFactory)
    {
        $this->balanceFactory = $balanceFactory;
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): Balance
    {
        return $this->build($data);
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(Balance $balance): array
    {
        return [
            'productUuid' => (string) $balance->productUuid(),
            'variantUuid' => (string) $balance->variantUuid(),
            'balance' => $balance->balance(),
        ];
    }

    /**
     * @param array $data
     *
     * @return Balance
     */
    private function build(array $data): Balance
    {
        return $this->balanceFactory->create(
            $data['productUuid'],
            $data['variantUuid'],
            $data['balance']
        );
    }
}
