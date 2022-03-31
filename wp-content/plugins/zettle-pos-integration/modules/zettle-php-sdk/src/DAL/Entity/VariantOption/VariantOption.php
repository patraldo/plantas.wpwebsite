<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\VariantOption;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageInterface;

class VariantOption
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * VariantOption constructor.
     *
     * @param string $name
     * @param string $value
     * @param ImageInterface|null $image
     */
    public function __construct(string $name, string $value, ImageInterface $image = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return VariantOption
     */
    public function setName(string $name): VariantOption
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return VariantOption
     */
    public function setValue(string $value): VariantOption
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return ImageInterface
     */
    public function image(): ?ImageInterface
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     */
    public function setImage(ImageInterface $image): VariantOption
    {
        $this->image = $image;

        return $this;
    }
}
