<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantInventoryState;

final class VariantInventoryState
{

    /**
     * @var string
     */
    private $uuid;

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
    private $locationType;

    /**
     * @var int
     */
    private $balance;

    /**
     * VariantChangeHistory constructor.
     *
     * @param string $locationUuid
     * @param string $productUuid
     * @param string $variantUuid
     * @param string $locationType
     * @param int $balance
     *
     */
    public function __construct(
        string $locationUuid,
        string $productUuid,
        string $variantUuid,
        string $locationType,
        int $balance
    ) {
        $this->uuid = $locationUuid;
        $this->productUuid = $productUuid;
        $this->variantUuid = $variantUuid;
        $this->locationType = $locationType;
        $this->balance = $balance;
    }

    /**
     * @return string
     */
    public function locationUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function productUuid(): string
    {
        return $this->productUuid;
    }

    /**
     * @return string
     */
    public function variantUuid(): string
    {
        return $this->variantUuid;
    }

    /**
     * @return string
     */
    public function locationType(): string
    {
        return $this->locationType;
    }

    /**
     * @return int
     */
    public function balance(): int
    {
        return $this->balance;
    }
}
