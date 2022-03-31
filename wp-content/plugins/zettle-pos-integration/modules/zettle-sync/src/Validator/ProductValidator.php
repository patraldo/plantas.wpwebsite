<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync\Validator;

use Inpsyde\Debug\ExceptionHandler;
use Inpsyde\WcProductContracts\ProductState;
use Inpsyde\WcProductContracts\ProductType;
use Inpsyde\Zettle\PhpSdk\Builder\BuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductInterface;
use Inpsyde\Zettle\PhpSdk\Exception\IdNotFoundException;
use Inpsyde\Zettle\PhpSdk\Exception\ValidatorException;
use Inpsyde\Zettle\PhpSdk\Map\OneToOneMapInterface;
use Inpsyde\Zettle\ProductSettings\Components\TermManager;
use Inpsyde\Zettle\Sync\Status\SyncStatusCodes;
use Throwable;
use WC_Product;

/**
 * Validates a product based on defined rules whether this is valid or not.
 *
 * @see ProductValidator::validate()
 *      Returns an empty array if the product is valid, otherwise you got an array
 *      with the conflicts, that doesn't got synced.
 * @see ProductValidator::validateWithLocalDBCheck()
 *      Returns the status synced or not, with the conflicts.
 */
class ProductValidator
{
    /**
     * @var OneToOneMapInterface
     */
    private $productMap;

    /**
     * @var TermManager
     */
    private $termManager;

    /**
     * @var string[]
     */
    private $allowedProductTypes;

    /**
     * @var bool
     */
    private $isBuildingAllowed;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var ExceptionHandler
     */
    private $exceptionHandler;

    /**
     * @param bool $isBuildingAllowed Whether it can try to build the product using $builder
     * to fully validate it.
     */
    public function __construct(
        OneToOneMapInterface $productMap,
        TermManager $termManager,
        array $allowedProductTypes,
        bool $isBuildingAllowed,
        BuilderInterface $builder,
        ExceptionHandler $exceptionHandler
    ) {

        $this->productMap = $productMap;
        $this->termManager = $termManager;
        $this->allowedProductTypes = $allowedProductTypes;
        $this->isBuildingAllowed = $isBuildingAllowed;
        $this->builder = $builder;
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * Return status codes array without the following status codes:
     * [synced, not-synced, syncable, not-syncable]
     *
     * @param int $productId
     *
     * @return string[]
     */
    public function validate(int $productId): array
    {
        $status = [];

        if ($productId === 0) {
            return [SyncStatusCodes::NO_VALID_PRODUCT_ID];
        }

        $product = wc_get_product($productId);

        if (!$product) {
            return [SyncStatusCodes::PRODUCT_NOT_FOUND];
        }

        $status = array_merge(
            $status,
            $this->validateProduct($product)
        );

        return $status;
    }

    /**
     * @param int $productId
     *
     * @return string[]
     */
    public function validateWithLocalDBCheck(int $productId): array
    {
        $status = [];

        if ($productId === 0) {
            return [SyncStatusCodes::NO_VALID_PRODUCT_ID];
        }

        try {
            $exists = $this->productMap->remoteId($productId);

            if (!empty($exists)) {
                return [SyncStatusCodes::SYNCED];
            }
        } catch (IdNotFoundException $exception) {
            $status[] = SyncStatusCodes::NOT_SYNCED;

            $product = wc_get_product($productId);

            if (!$product) {
                return [SyncStatusCodes::PRODUCT_NOT_FOUND];
            }

            $status = array_merge(
                $status,
                $this->validateProduct($product)
            );
        }

        if (in_array(SyncStatusCodes::NOT_SYNCED, $status, true) && count($status) > 1) {
            array_splice($status, 1, 0, [SyncStatusCodes::NOT_SYNCABLE]);
        }

        if (in_array(SyncStatusCodes::NOT_SYNCED, $status, true) && count($status) === 1) {
            $status[] = SyncStatusCodes::SYNCABLE;
        }

        return $status;
    }

    /**
     * @param WC_Product $product
     *
     * @return string[]
     */
    protected function validateProduct(WC_Product $product): array
    {
        $productType = (string) $product->get_type();

        if (!$product->is_type(ProductType::VARIATION) && !in_array($productType, $this->allowedProductTypes, true)) {
            return [SyncStatusCodes::UNSUPPORTED_PRODUCT_TYPE];
        }

        $status = [];

        if (!$product->is_purchasable()) {
            $status[] = SyncStatusCodes::UNPURCHASABLE;
        }

        if ($this->termManager->hasTerm((int) $product->get_id())) {
            $status[] = SyncStatusCodes::EXCLUDED;
        }

        if ($product->get_status() !== ProductState::PUBLISH) {
            $status[] = SyncStatusCodes::UNPUBLISHED;
        }

        if ($product->get_catalog_visibility() === 'hidden') {
            $status[] = SyncStatusCodes::INVISIBLE;
        }

        if (!$this->isBuildingAllowed) {
            return $status;
        }

        if (in_array(SyncStatusCodes::UNPUBLISHED, $status, true)) {
            // some required properties like date may be missing on drafts, so not trying to build
            return $status;
        }

        try {
            $this->builder->build(ProductInterface::class, $product, $this->builder);
        } catch (ValidatorException $exception) {
            $status = array_merge($status, $exception->errorCodes());
        } catch (Throwable $exception) {
            $status[] = SyncStatusCodes::UNDEFINED;

            $this->exceptionHandler->handle($exception);
        }

        return $status;
    }
}
