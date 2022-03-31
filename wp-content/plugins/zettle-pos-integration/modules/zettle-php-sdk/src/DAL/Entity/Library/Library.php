<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Library;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Discount\DiscountCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductCollection;

final class Library
{
    /**
     * @var string
     */
    private $untilEventLogUuid;

    /**
     * @var string
     */
    private $fromEventLogUuid;

    /**
     * @var ProductCollection
     */
    private $products;

    /**
     * @var DiscountCollection
     */
    private $discounts;

    /**
     * @var ProductCollection|null
     */
    private $deletedProducts;

    /**
     * @var DiscountCollection|null
     */
    private $deletedDiscounts;

    /**
     * Library constructor.
     *
     * @param string $untilEventLogUuid
     * @param string $fromEventLogUuid
     * @param ProductCollection $products
     * @param DiscountCollection $discounts
     * @param ProductCollection|null $deletedProducts
     * @param DiscountCollection|null $deletedDiscounts
     */
    public function __construct(
        string $untilEventLogUuid,
        string $fromEventLogUuid,
        ProductCollection $products,
        DiscountCollection $discounts,
        ?ProductCollection $deletedProducts = null,
        ?DiscountCollection $deletedDiscounts = null
    ) {

        $this->untilEventLogUuid = $untilEventLogUuid;
        $this->products = $products;
        $this->discounts = $discounts;
        $this->deletedProducts = $deletedProducts;
        $this->deletedDiscounts = $deletedDiscounts;
        $this->fromEventLogUuid = $fromEventLogUuid;
    }

    /**
     * @return string
     */
    public function untilEventLogUuid(): string
    {
        return $this->untilEventLogUuid;
    }

    /**
     * @return string|null
     */
    public function fromEventLogUuid(): ?string
    {
        return $this->fromEventLogUuid;
    }

    /**
     * @return ProductCollection
     */
    public function products(): ProductCollection
    {
        return $this->products;
    }

    /**
     * @return DiscountCollection
     */
    public function discounts(): DiscountCollection
    {
        return $this->discounts;
    }

    /**
     * @return ProductCollection|null
     */
    public function deletedProducts(): ?ProductCollection
    {
        return $this->deletedProducts;
    }

    /**
     * @return DiscountCollection|null
     */
    public function deletedDiscounts(): ?DiscountCollection
    {
        return $this->deletedDiscounts;
    }
}
