<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk;

use Psr\Container\ContainerInterface;

/**
 * Class NamespacedContainer
 * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
 * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
 *
 * @package Inpsyde\Zettle\PhpSdk
 */
class NamespacedContainer implements ContainerInterface
{

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ContainerInterface
     */
    private $base;

    public function __construct(string $namespace, ContainerInterface $base)
    {
        $this->namespace = $namespace;
        $this->base = $base;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        return $this->base->get("{$this->namespace}.$id");
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $this->base->has("{$this->namespace}.$id");
    }
}
