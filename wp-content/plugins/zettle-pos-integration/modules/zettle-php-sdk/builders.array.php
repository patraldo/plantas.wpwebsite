<?php

declare(strict_types=1);

// phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

use Inpsyde\Zettle\PhpSdk\Builder\BuilderInterface as B;
use Inpsyde\Zettle\PhpSdk\Builder\CallbackBuilder;
use Inpsyde\Zettle\PhpSdk\Builder\ImageBuilder;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory\Inventory;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Location\Location;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Location\Type\LocationType;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Metadata\Metadata;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Metadata\Source;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Organization\Organization;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Organization\TaxationMode;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Organization\TaxationType;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Presentation\Presentation;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Price\Price;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\Product;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Tax\TaxRate;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant\Variant;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant\VariantCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant\VariantInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantInventoryState\VariantInventoryState;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantInventoryState\VariantInventoryStateCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption\VariantOption;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption\VariantOptionCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;
use Psr\Container\ContainerInterface as C;

$key = static function (string $className): string {
    return "zettle.sdk.builder.array.{$className}";
};
$builder = static function (callable $callable) {
    return static function (C $container) use ($callable): B {
        return new CallbackBuilder(
            static function (string $className, $payload, B $builder) use ($callable, $container) {
                return $callable($payload, $builder, $container);
            }
        );
    };
};

return [
    $key(Presentation::class) => $builder(
        static function (array $payload, B $builder): Presentation {
            $backgroundColor = isset($payload['backgroundColor'])
                ? $payload['backgroundColor']
                : null;
            $textColor = isset($payload['textColor'])
                ? $payload['textColor']
                : null;

            return new Presentation(
                $builder->build(ImageInterface::class, $payload),
                $backgroundColor,
                $textColor
            );
        }
    ),
    //<editor-fold desc="Images">
    $key(ImageInterface::class) => static function (C $container): B {
        return new ImageBuilder();
    },
    $key(ImageCollection::class) => $builder(
        static function (array $payload, B $builder): ImageCollection {
            // phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
            $images = array_map(
                static function ($imagePayload) use ($builder) {
                    return $builder->build(ImageInterface::class, (array) $imagePayload);
                },
                $payload
            );

            // phpcs:enable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

            return new ImageCollection(...$images);
        }
    ),
    //</editor-fold>
    //<editor-fold desc="Variants">
    $key(VariantInterface::class) => $builder(
        static function (array $payload, B $builder): Variant {
            $presentation = isset($payload['presentation'])
                ? $builder->build(Presentation::class, $payload['presentation'])
                : null;

            $options = isset($payload['options'])
                ? $builder->build(VariantOptionCollection::class, $payload['options'])
                : new VariantOptionCollection();

            $vatPercentage = isset($payload['vatPercentage'])
                ? new Vat((float) $payload['vatPercentage'])
                : null;

            $costPrice = isset($payload['costPrice'])
                ? $builder->build(Price::class, $payload['costPrice'])
                : null;

            $defaultQuantity = isset($payload['defaultQuantity'])
                ? (int) $payload['defaultQuantity']
                : 0;
            $unitName = isset($payload['unitName'])
                ? $payload['unitName']
                : null;
            $barcode = isset($payload['barcode'])
                ? $payload['barcode']
                : null;

            return new Variant(
                $payload['uuid'],
                $payload['name'] ?? '',
                $payload['description'] ?? '',
                $payload['sku'] ?? '',
                $defaultQuantity,
                $builder->build(Price::class, $payload['price'] ?? []),
                $vatPercentage,
                $presentation,
                $options,
                $unitName,
                $costPrice,
                $barcode
            );
        }
    ),
    $key(VariantCollection::class) => $builder(
        static function (array $payload, B $builder): VariantCollection {
            $variants = array_map(
                static function (array $state) use ($builder) {
                    return $builder->build(VariantInterface::class, $state);
                },
                $payload
            );

            return new VariantCollection(...$variants);
        }
    ),
    $key(VariantOption::class) => $builder(
        static function (array $payload, B $builder): VariantOption {
            return new VariantOption(
                $payload['name'],
                $payload['value']
            );
        }
    ),
    $key(VariantOptionCollection::class) => $builder(
        static function (array $payload, B $builder): VariantOptionCollection {
            $variants = array_map(
                static function (array $state) use ($builder) {
                    return $builder->build(VariantOption::class, $state);
                },
                $payload
            );

            return new VariantOptionCollection(...$variants);
        }
    ),
    //</editor-fold>
    //<editor-fold desc="Products">
    $key(ProductInterface::class) => $builder(
        static function (array $payload, B $builder): ProductInterface {
            $presentation = isset($payload['presentation'])
                ? $builder->build(Presentation::class, $payload['presentation'])
                : null;
            $updated = isset($payload['updated'])
                ? new DateTime($payload['updated'])
                : null;
            $updatedBy = isset($payload['updatedBy'])
                ? $payload['updatedBy']
                : null;
            $created = isset($payload['created'])
                ? new DateTime($payload['created'])
                : null;
            $vatPercentage = isset($payload['vatPercentage'])
                ? new Vat((float) $payload['vatPercentage'])
                : null;
            $taxExempt = isset($payload['taxExempt'])
                ? (bool) $payload['taxExempt']
                : null;
            $externalReference = isset($payload['externalReference'])
                ? $payload['externalReference']
                : null;
            $etag = isset($payload['etag'])
                ? $payload['etag']
                : null;
            $unitName = isset($payload['unitName'])
                ? $payload['unitName']
                : null;
            $metadata = isset($payload['metadata'])
                ? $builder->build(Metadata::class, $payload['metadata'])
                : null;

            return new Product(
                $payload['uuid'],
                $payload['name'],
                $payload['description'] ?? '',
                $builder->build(ImageCollection::class, $payload['imageLookupKeys']),
                $builder->build(VariantCollection::class, $payload['variants']),
                $presentation,
                $externalReference,
                $etag,
                $updated,
                $updatedBy,
                $created,
                $vatPercentage,
                $taxExempt,
                null,
                $unitName,
                $metadata
            );
        }
    ),
    $key(ProductCollection::class) => $builder(
        static function (array $payload, B $builder): ProductCollection {
            $products = array_map(
                static function (array $state) use ($builder) {
                    return $builder->build(ProductInterface::class, $state);
                },
                $payload
            );

            return new ProductCollection(...$products);
        }
    ),
    //</editor-fold>
    //<editor-fold desc="Inventory">
    $key(VariantInventoryState::class) => $builder(
        static function (array $payload, B $builder): VariantInventoryState {
            return new VariantInventoryState(
                $payload['locationUuid'],
                $payload['productUuid'],
                $payload['variantUuid'],
                $payload['locationType'],
                (int) $payload['balance']
            );
        }
    ),
    $key(VariantInventoryStateCollection::class) => $builder(
        static function (
            array $payload,
            B $builder
        ): VariantInventoryStateCollection {
            $states = array_map(
                static function (array $state) use ($builder) {
                    return $builder->build(VariantInventoryState::class, $state);
                },
                $payload
            );

            return new VariantInventoryStateCollection($states);
        }
    ),
    $key(Inventory::class) => $builder(
        static function (array $payload, B $builder): Inventory {
            $changeHistory = $builder->build(VariantInventoryStateCollection::class, $payload['variants']);

            return new Inventory($changeHistory);
        }
    ),
    $key(Location::class) => $builder(
        static function (array $payload, B $builder): Location {
            $isDefault = isset($payload['default'])
                ? (bool) $payload['default']
                : false;
            $description = isset($payload['description'])
                ? $payload['description']
                : null;

            return new Location(
                $payload['uuid'],
                LocationType::get($payload['type']),
                $payload['name'],
                $description,
                $isDefault
            );
        }
    ),
    $key(TaxRate::class) => $builder(
        static function (array $payload, B $builder): TaxRate {
            $percentage = $payload['percentage']
                ? (float) $payload['percentage']
                : null;
            $default = isset($payload['default'])
                ? (bool) $payload['default']
                : false;

            return new TaxRate(
                $payload['uuid'],
                $payload['label'],
                $percentage,
                $default
            );
        }
    ),
    //</editor-fold>
    $key(Price::class) => $builder(
        static function (array $payload, B $builder, C $container): Price {
            $wooConfig = $container->get('zettle.sdk.config.woocommerce-config');

            $amount = isset($payload['amount'])
                ? (int) $payload['amount']
                : 0;
            $currency = isset($payload['currencyId'])
                ? $payload['currencyId']
                : $wooConfig->get('currency');

            return new Price($amount, $currency);
        }
    ),
    $key(Vat::class) => $builder(
        static function (array $payload, B $builder): Vat {
            return new Vat((float) current($payload));
        }
    ),
    $key(Metadata::class) => $builder(
        static function (array $payload, B $builder): Metadata {
            return new Metadata(
                $payload['inPos'],
                $builder->build(Source::class, $payload['source'])
            );
        }
    ),
    $key(Source::class) => $builder(
        static function (array $payload, B $builder): Source {
            return new Source($payload['name'], $payload['external']);
        }
    ),
    $key(Organization::class) => $builder(
        static function (array $payload, B $builder, C $container): Organization {
            $created = isset($payload['created'])
                ? new DateTime($payload['created']) : null;
            $organizationId = isset($payload['organizationId'])
                ? (int) $payload['organizationId'] : null;
            $taxationMode = isset($payload['taxationMode'])
                ? (string) $payload['taxationMode'] : TaxationMode::INCLUSIVE;
            $taxationType = isset($payload['taxationType'])
                ? (string) $payload['taxationType'] : TaxationType::VAT;
            $timezone = isset($payload['timeZone'])
                ? new DateTimeZone($payload['timeZone']) : null;

            return new Organization(
                $payload['uuid'],
                $taxationType === TaxationType::VAT ? new Vat($payload['vatPercentage']) : null,
                $payload['currency'],
                $payload['name'] ?? null,
                $payload['city'] ?? null,
                $payload['zipCode'] ?? null,
                $payload['address'] ?? null,
                $payload['phoneNumber'] ?? null,
                $payload['contactEmail'] ?? null,
                $payload['receiptEmail'] ?? null,
                $payload['legalEntityType'] ?? null,
                $payload['legalEntityNr'] ?? null,
                $payload['country'] ?? null,
                $payload['language'] ?? null,
                $created,
                $payload['ownerUuid'] ?? null,
                $organizationId,
                $payload['customerStatus'] ?? null,
                $taxationMode,
                $taxationType,
                $payload['customerType'] ?? null,
                $timezone,
                $payload['addressLine2'] ?? null,
                $payload['legalName'] ?? null,
                $payload['legalZipCode'] ?? null,
                $payload['legalCity'] ?? null,
                $payload['legalState'] ?? null
            );
        }
    ),
];
