<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Presentation\Presentation;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Price\Price;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption\VariantOptionCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;

/**
 * Class Variant
 * phpcs:disable Inpsyde.CodeQuality.PropertyPerClassLimit.TooMuchProperties
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors *
 *
 * @package Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant
 */
class Variant implements VariantTransferInterface, StockQuantityAwareInterface, PriceAwareInterface
{

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var Price|null
     */
    private $price;

    /**
     * @var Vat|null
     */
    private $vat;

    /**
     * @var int
     */
    private $defaultQuantity;

    /**
     * @var Presentation
     */
    private $presentation;

    /**
     * @var VariantOptionCollection
     */
    private $options;

    /**
     * @var string|null
     */
    private $unitName;

    /**
     * @var Price|null
     */
    private $costPrice;

    /**
     * @var string|null
     */
    private $barcode;

    /**
     * Variant constructor.
     *
     * @param string $uuid
     * @param string $name
     * @param string $description
     * @param string $sku
     * @param int $defaultQuantity
     * @param Price|null $price
     * @param Vat|null $vat
     * @param Presentation|null $presentation
     * @param VariantOptionCollection|null $options
     * @param string|null $unitName
     * @param Price|null $costPrice
     * @param string|null $barcode
     */
    public function __construct(
        string $uuid,
        string $name,
        string $description,
        string $sku,
        int $defaultQuantity,
        ?Price $price = null,
        ?Vat $vat = null,
        ?Presentation $presentation = null,
        ?VariantOptionCollection $options = null,
        ?string $unitName = null,
        ?Price $costPrice = null,
        ?string $barcode = null
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->sku = $sku;
        $this->price = $price;
        $this->defaultQuantity = $defaultQuantity;
        $this->vat = $vat;
        $this->presentation = $presentation;
        $this->options = $options;
        $this->unitName = $unitName;
        $this->costPrice = $costPrice;
        $this->barcode = $barcode;
    }

    /**
     * @return string
     */
    public function uuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function sku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return Price|null
     */
    public function price(): ?Price
    {
        return $this->price;
    }

    /**
     * @param Price|null $price
     */
    public function setPrice(?Price $price): void
    {
        $this->price = $price;
    }

    /**
     * @return Vat
     */
    public function vat(): ?Vat
    {
        return $this->vat;
    }

    /**
     * @param Vat $vat
     */
    public function setVat(?Vat $vat): void
    {
        $this->vat = $vat;
    }

    /**
     * @return int
     */
    public function defaultQuantity(): int
    {
        return $this->defaultQuantity;
    }

    /**
     * @param int $defaultQuantity
     *
     * @return void
     */
    public function setDefaultQuantity(int $defaultQuantity): void
    {
        $this->defaultQuantity = $defaultQuantity;
    }

    /**
     * @return Presentation|null
     */
    public function presentation(): ?Presentation
    {
        return $this->presentation;
    }

    /**
     * @param Presentation $presentation
     */
    public function setPresentation(Presentation $presentation): void
    {
        $this->presentation = $presentation;
    }

    /**
     * @return VariantOptionCollection|null
     */
    public function options(): ?VariantOptionCollection
    {
        return $this->options;
    }

    /**
     * @param VariantOptionCollection $options
     */
    public function setOptions(VariantOptionCollection $options): void
    {
        $this->options = $options;
    }

    /**
     * @return string|null
     */
    public function unitName(): ?string
    {
        return $this->unitName;
    }

    /**
     * @param string|null $unitName
     */
    public function setUnitName(string $unitName): void
    {
        $this->unitName = $unitName;
    }

    /**
     * @return Price|null
     */
    public function costPrice(): ?Price
    {
        return $this->costPrice;
    }

    /**
     * @param Price|null $costPrice
     */
    public function setCostPrice(Price $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    /**
     * @return string|null
     */
    public function barcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string|null $barcode
     */
    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }
}
