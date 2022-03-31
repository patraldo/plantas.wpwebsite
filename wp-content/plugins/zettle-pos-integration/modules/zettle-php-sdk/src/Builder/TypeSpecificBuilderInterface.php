<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Builder;

interface TypeSpecificBuilderInterface extends BuilderInterface
{

    /**
     * @param $payload
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * @return bool
     */
    public function accepts($payload): bool;
}
