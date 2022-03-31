<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Product;

final class ProductCollection
{

    /**
     * @var ProductInterface[]
     */
    private $collection = [];

    /**
     * ProductCollection constructor.
     *
     * @param array $products
     */
    public function __construct(ProductInterface ...$products)
    {
        foreach ($products as $product) {
            $this->add($product);
        }
    }

    /**
     * @param ProductInterface $product
     *
     * @return ProductCollection
     */
    public function add(ProductInterface $product): self
    {
        $this->collection[spl_object_hash($product)] = $product;

        return $this;
    }

    /**
     * @param ProductInterface $product
     *
     * @return ProductCollection
     */
    public function remove(ProductInterface $product): self
    {
        unset($this->collection[spl_object_hash($product)]);

        return $this;
    }

    /**
     * @param string $uuid
     *
     * @return ProductInterface
     */
    public function get(string $uuid): ProductInterface
    {
        foreach ($this->collection as $item) {
            if ($item->uuid() === (string) $uuid) {
                return $item;
            }
        }
    }

    /**
     * @return ProductInterface[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * @return ProductCollection
     */
    public function reset(): self
    {
        $this->collection = [];

        return $this;
    }
}
