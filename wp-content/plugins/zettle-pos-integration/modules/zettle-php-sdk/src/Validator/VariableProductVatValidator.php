<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Validator;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Product\ProductInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant\VariantInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\DifferentVariantVatException;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
class VariableProductVatValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function accepts($entity): bool
    {
        return $entity instanceof ProductInterface && !empty($entity->variants()->all());
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     * @throws DifferentVariantVatException
     */
    public function validate($product): bool
    {
        assert($product instanceof ProductInterface);

        $this->checkVat($product);

        return true;
    }

    private function checkVat(ProductInterface $product): void
    {
        $vats = array_merge(
            [$product->vat()],
            array_map(static function (VariantInterface $variant): ?Vat {
                return $variant->vat();
            }, $product->variants()->all())
        );

        $uniqueVats = array_unique(array_map(static function (?Vat $vat): ?float {
            return $vat ? $vat->percentage() : null;
        }, $vats));

        if (count($uniqueVats) > 1) {
            throw new DifferentVariantVatException($product->name(), $uniqueVats);
        }
    }
}
