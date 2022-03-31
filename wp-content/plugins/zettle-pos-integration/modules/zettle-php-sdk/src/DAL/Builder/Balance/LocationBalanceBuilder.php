<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Balance;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\LocationBalance;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\LocationBalanceFactory;

class LocationBalanceBuilder implements LocationBalanceBuilderInterface
{
    /**
     * @var LocationBalanceFactory
     */
    private $locationBalanceFactory;

    public function __construct(LocationBalanceFactory $locationBalanceFactory)
    {
        $this->locationBalanceFactory = $locationBalanceFactory;
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): LocationBalance
    {
        return $this->build($data);
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(LocationBalance $locationBalance): array
    {
        return [
            'locationUuid' => (string) $locationBalance->locationUuid(),
            'locationType' => $locationBalance->locationType()->getValue(),
            'productUuid' => (string) $locationBalance->productUuid(),
            'variantUuid' => (string) $locationBalance->variantUuid(),
            'balance' => $locationBalance->balance(),
        ];
    }

    /**
     * @param array $data
     *
     * @return LocationBalance
     */
    private function build(array $data): LocationBalance
    {
        return $this->locationBalanceFactory->create(
            $data['locationUuid'],
            $data['locationType'],
            $data['productUuid'],
            $data['variantUuid'],
            $data['balance']
        );
    }
}
