<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Serializer;

use Psr\Container\ContainerInterface;

class ContainerAwareEntitySerializer implements SerializerInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $entity
     * @param SerializerInterface|null $serializer
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     *
     * @return array
     */
    public function serialize($entity, ?SerializerInterface $serializer = null): array
    {
        $className = get_class($entity);
        /**
         * Try to find a serializer for the concrete class
         */
        if ($this->container->has($className)) {
            $concreteSerializer = $this->container->get($className);
            assert($concreteSerializer instanceof SerializerInterface);

            return $concreteSerializer->serialize($entity, $this);
        }
        /**
         * If none was found, try to serialize based on implemented interfaces.
         * Since there can be multiple interfaces, we will merge all of them into one array lol
         */
        $interfaces = class_implements($className);

        return array_merge(
            ...array_values(
                array_map(
                    function ($interface) use ($entity): array {
                        if (!$this->container->has($interface)) {
                            return [];
                        }
                        $interfaceSerializer = $this->container->get($interface);
                        assert($interfaceSerializer instanceof SerializerInterface);

                        return $interfaceSerializer->serialize($entity, $this);
                    },
                    $interfaces
                )
            )
        );
    }
}
