<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Validator;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

use Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption\VariantOptionDefinitions;
// phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
use Inpsyde\Zettle\PhpSdk\Exception\Validator\VariantOptionDefinitions\EmptyVariantOptionCollectionException;
// phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
use Inpsyde\Zettle\PhpSdk\Exception\Validator\VariantOptionDefinitions\EmptyVariantOptionDefinitionsException;
// phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
use Inpsyde\Zettle\PhpSdk\Exception\Validator\VariantOptionDefinitions\MaximumVariantOptionDefinitionsAmountException;

class VariantOptionDefinitionsValidator implements ValidatorInterface
{

    public const MAXIMUM_DEFINITIONS_AMOUNT = 3;

    /**
     * @inheritDoc
     */
    public function accepts($entity): bool
    {
        return $entity instanceof VariantOptionDefinitions;
    }

    /**
     * @inheritDoc
     */
    public function validate($entity): bool
    {
        assert($entity instanceof VariantOptionDefinitions);

        if (empty($entity->definitions())) {
            throw new EmptyVariantOptionDefinitionsException();
        }

        $emptyVariantOptions = $this->validateVariantOptionDefinitions($entity);

        if (!empty($emptyVariantOptions)) {
            throw new EmptyVariantOptionCollectionException($emptyVariantOptions);
        }

        $amount = count($entity->definitions());

        /**
         * Count the VariantOptionDefinitions, because we need to verify this,
         * the Zettle API allows only a certain amount of different VariantOptionDefinitions
         *
         * Remove if this restriction got resolved by Zettle => IZET-285
         */
        if ($amount > self::MAXIMUM_DEFINITIONS_AMOUNT) {
            throw new MaximumVariantOptionDefinitionsAmountException(
                self::MAXIMUM_DEFINITIONS_AMOUNT,
                $amount
            );
        }

        return true;
    }

    /**
     * @param VariantOptionDefinitions $definitions
     *
     * @return string[]
     */
    private function validateVariantOptionDefinitions(VariantOptionDefinitions $definitions): array
    {
        $emptyProperties = [];

        foreach ($definitions->definitions() as $name => $properties) {
            $props = $properties->all();

            if (!empty($props)) {
                continue;
            }

            $emptyProperties[] = $name;
        }

        return $emptyProperties;
    }
}
