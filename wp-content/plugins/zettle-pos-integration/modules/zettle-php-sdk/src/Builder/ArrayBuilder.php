<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Builder;

/**
 * Class ArrayBuilder
 * Decorates another Builder, but registers it only for arrays
 *
 * @package Inpsyde\Zettle\PhpSdk\Builder
 */
class ArrayBuilder implements TypeSpecificBuilderInterface
{

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @inheritDoc
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @inheritDoc
     */
    public function build(string $className, $payload, ?BuilderInterface $builder = null)
    {
        return $this->builder->build($className, $payload, $builder ?? $this);
    }

    /**
     * @param $payload
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     *
     * @return bool
     */
    public function accepts($payload): bool
    {
        return is_array($payload);
    }
}
