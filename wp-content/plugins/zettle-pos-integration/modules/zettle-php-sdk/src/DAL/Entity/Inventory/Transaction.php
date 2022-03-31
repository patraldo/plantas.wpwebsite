<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory;

/**
 * Class Transaction
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors
 *
 * @package Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory
 */
class Transaction
{

    /**
     * @var string
     */
    private $productUuid;

    /**
     * @var string
     */
    private $variantUuid;

    /**
     * @var string
     */
    private $fromLocationUuid;

    /**
     * @var string
     */
    private $toLocationUuid;

    /**
     * @var int
     */
    private $change;

    public function __construct(
        string $productUuid,
        string $variantUuid,
        string $fromLocationUuid,
        string $toLocationUuid,
        int $change
    ) {
        $this->productUuid = $productUuid;
        $this->variantUuid = $variantUuid;
        $this->fromLocationUuid = $fromLocationUuid;
        $this->toLocationUuid = $toLocationUuid;
        $this->change = $change;
    }

    /**
     * @return string
     */
    public function productUuid(): string
    {
        return $this->productUuid;
    }

    /**
     * @param string $productUuid
     */
    public function setProductUuid(string $productUuid): void
    {
        $this->productUuid = $productUuid;
    }

    /**
     * @return string
     */
    public function variantUuid(): string
    {
        return $this->variantUuid;
    }

    /**
     * @param string $variantUuid
     */
    public function setVariantUuid(string $variantUuid): void
    {
        $this->variantUuid = $variantUuid;
    }

    /**
     * @return string
     */
    public function fromLocationUuid(): string
    {
        return $this->fromLocationUuid;
    }

    /**
     * @param string $fromLocationUuid
     */
    public function setFromLocationUuid(string $fromLocationUuid): void
    {
        $this->fromLocationUuid = $fromLocationUuid;
    }

    /**
     * @return string
     */
    public function toLocationUuid(): string
    {
        return $this->toLocationUuid;
    }

    /**
     * @param string $toLocationUuid
     */
    public function setToLocationUuid(string $toLocationUuid): void
    {
        $this->toLocationUuid = $toLocationUuid;
    }

    /**
     * @return int
     */
    public function change(): int
    {
        return $this->change;
    }

    /**
     * @param int $change
     */
    public function setChange(int $change): void
    {
        $this->change = $change;
    }
}
