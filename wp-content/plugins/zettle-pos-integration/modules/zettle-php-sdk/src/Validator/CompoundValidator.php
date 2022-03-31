<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Validator;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

class CompoundValidator implements ValidatorInterface
{

    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * CompoundValidator constructor.
     *
     * @param ValidatorInterface ...$validatorInterfaces
     */
    public function __construct(ValidatorInterface ...$validatorInterfaces)
    {
        $this->validators = $validatorInterfaces;
    }

    /**
     * @inheritDoc
     */
    public function accepts($entity): bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->accepts($entity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function validate($entity): bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->accepts($entity)) {
                $validator->validate($entity);
            }
        }

        return true;
    }
}
