<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Product;

use DateTime;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Metadata\Metadata;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Presentation\Presentation;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant\VariantCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption\VariantOptionDefinitions;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\VatSetterInterface;

/**
 * Interface WritableProductInterface
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors
 *
 * @package Inpsyde\Zettle\PhpSdk\DAL\Entity\Product
 */
interface WritableProductInterface extends VatSetterInterface
{

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @param ImageCollection $imageCollection
     */
    public function setImages(ImageCollection $imageCollection): void;

    /**
     * @param Presentation $presentation
     */
    public function setPresentation(Presentation $presentation): void;

    /**
     * @param VariantCollection $variants
     */
    public function setVariants(VariantCollection $variants): void;

    /**
     * @param string $externalReference
     */
    public function setExternalReference(string $externalReference): void;

    /**
     * @param string $etag
     */
    public function setEtag(string $etag): void;

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void;

    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy(string $updatedBy): void;

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void;

    public function setTaxExempt(?bool $taxExempt): void;

    public function setUsesDefaultTax(?bool $usesDefaultTax): void;

    /**
     * @param string $unitName
     */
    public function setUnitName(string $unitName): void;

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata): void;

    public function setVariantOptionDefinitions(
        VariantOptionDefinitions $definitions
    ): void;
}
