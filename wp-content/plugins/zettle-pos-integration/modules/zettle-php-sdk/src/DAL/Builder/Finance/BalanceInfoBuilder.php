<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Finance;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance\BalanceInfo;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance\BalanceInfoFactory;

class BalanceInfoBuilder implements BalanceInfoBuilderInterface
{
    /**
     * @var BalanceInfoFactory
     */
    private $balanceInfoFactory;

    /**
     * BalanceInfoBuilder constructor.
     * @param BalanceInfoFactory $balanceInfoFactory
     */
    public function __construct(BalanceInfoFactory $balanceInfoFactory)
    {
        $this->balanceInfoFactory = $balanceInfoFactory;
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): BalanceInfo
    {
        return $this->build($data);
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(BalanceInfo $balanceInfo): array
    {
        return [
            'totalBalance' => $balanceInfo->totalBalance(),
            'currencyId' => $balanceInfo->currencyId(),
        ];
    }

    /**
     * @param array $data
     *
     * @return BalanceInfo
     */
    private function build(array $data): BalanceInfo
    {
        return $this->balanceInfoFactory->create(
            $data['totalBalance'],
            $data['currencyId']
        );
    }
}
