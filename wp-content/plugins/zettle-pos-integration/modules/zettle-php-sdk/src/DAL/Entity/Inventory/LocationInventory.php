<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Balance\LocationBalanceCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductCollection;

final class LocationInventory
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var ProductCollection
     */
    private $trackedProducts;

    /**
     * @var LocationBalanceCollection
     */
    private $locationBalances;

    /**
     * LocationInventory constructor.
     *
     * @param string $uuid
     * @param ProductCollection $trackedProducts
     * @param LocationBalanceCollection $locationBalances
     */
    public function __construct(
        string $uuid,
        ProductCollection $trackedProducts,
        LocationBalanceCollection $locationBalances
    ) {

        $this->uuid = $uuid;
        $this->trackedProducts = $trackedProducts;
        $this->locationBalances = $locationBalances;
    }

    /**
     * @return string
     */
    public function uuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return ProductCollection
     */
    public function trackedProducts(): ProductCollection
    {
        return $this->trackedProducts;
    }

    /**
     * @return LocationBalanceCollection
     */
    public function locationBalances(): LocationBalanceCollection
    {
        return $this->locationBalances;
    }
}
