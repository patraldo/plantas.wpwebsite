<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Balance;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\LocationBalanceCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\LocationBalanceCollectionFactory;

class LocationBalanceCollectionBuilder implements LocationBalanceCollectionBuilderInterface
{
    /**
     * @var LocationBalanceCollectionFactory
     */
    private $locationBalanceCollectionFactory;

    /**
     * @var LocationBalanceBuilder
     */
    private $locationBalanceBuilder;

    /**
     * LocationBalanceCollectionBuilder constructor.
     *
     * @param LocationBalanceCollectionFactory $locationBalanceCollectionFactory
     * @param LocationBalanceBuilderInterface $locationBalanceBuilder
     */
    public function __construct(
        LocationBalanceCollectionFactory $locationBalanceCollectionFactory,
        LocationBalanceBuilderInterface $locationBalanceBuilder
    ) {
        $this->locationBalanceCollectionFactory = $locationBalanceCollectionFactory;
        $this->locationBalanceBuilder = $locationBalanceBuilder;
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): LocationBalanceCollection
    {
        $locationBalanceCollection = $this->locationBalanceCollectionFactory->create();

        foreach ($data as $locationBalance) {
            $locationBalanceCollection->add(
                $this->locationBalanceBuilder->buildFromArray($locationBalance)
            );
        }

        return $locationBalanceCollection;
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(LocationBalanceCollection $locationBalanceCollection): array
    {
        $data = [];

        foreach ($locationBalanceCollection->all() as $locationBalance) {
            $data[][] = $this->locationBalanceBuilder->createDataArray($locationBalance);
        }

        return $data;
    }
}
