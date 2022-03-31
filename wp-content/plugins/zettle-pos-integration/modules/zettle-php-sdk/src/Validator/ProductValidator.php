<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Validator;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductInterface;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\MaximumVariantsException;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\MinimumVariantsException;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
class ProductValidator implements ValidatorInterface
{

    public const MINIMUM_VARIANTS_AMOUNT = 0;
    public const MAXIMUM_VARIANTS_AMOUNT = 99;

    /**
     * @inheritDoc
     */
    public function accepts($entity): bool
    {
        return $entity instanceof ProductInterface;
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     * @throws MaximumVariantsException
     *
     * @throws MinimumVariantsException
     */
    public function validate($product): bool
    {
        assert($product instanceof ProductInterface);

        $this->validateMinimumVariants($product);
        $this->validateMaximumVariants($product);

        return true;
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     *
     * @throws MinimumVariantsException
     *
     */
    public function validateMinimumVariants(ProductInterface $product): bool
    {
        if (count($product->variants()->all()) === self::MINIMUM_VARIANTS_AMOUNT) {
            throw new MinimumVariantsException($product->name());
        }

        return true;
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     *
     * @throws MaximumVariantsException
     *
     */
    public function validateMaximumVariants(ProductInterface $product): bool
    {
        $variantsAmount = count($product->variants()->all());

        if ($variantsAmount > self::MAXIMUM_VARIANTS_AMOUNT) {
            throw new MaximumVariantsException($product->name(), $variantsAmount);
        }

        return true;
    }
}
