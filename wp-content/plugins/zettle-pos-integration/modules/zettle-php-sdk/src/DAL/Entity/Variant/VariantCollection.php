<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant;

final class VariantCollection
{

    /**
     * @var VariantInterface[]
     */
    private $collection = [];

    /**
     * VariantCollection constructor.
     *
     * @param VariantInterface[] $variants
     */
    public function __construct(VariantInterface ...$variants)
    {
        foreach ($variants as $variant) {
            if ($variant instanceof VariantInterface) {
                $this->add($variant);
            }
        }
    }

    /**
     * @param VariantInterface $variant
     *
     * @return VariantCollection
     */
    public function add(VariantInterface $variant): self
    {
        $this->collection[spl_object_hash($variant)] = $variant;

        return $this;
    }

    /**
     * @param VariantInterface $variant
     *
     * @return VariantCollection
     */
    public function remove(VariantInterface $variant): self
    {
        unset($this->collection[spl_object_hash($variant)]);

        return $this;
    }

    /**
     * @param string $uuid
     *
     * @return VariantInterface
     */
    public function get(string $uuid): VariantInterface
    {
        foreach ($this->collection as $item) {
            if ((string) $item->uuid() === $uuid) {
                return $item;
            }
        }
    }

    /**
     * @return Variant[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * @return VariantCollection
     */
    public function reset(): self
    {
        $this->collection = [];

        return $this;
    }
}
