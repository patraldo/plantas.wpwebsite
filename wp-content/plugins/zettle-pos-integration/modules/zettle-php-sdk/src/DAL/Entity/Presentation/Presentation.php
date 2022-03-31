<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Presentation;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ZettleImage;

class Presentation
{

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * @var string
     */
    private $backgroundColor;

    /**
     * @var string
     */
    private $textColor;

    /**
     * Presentation constructor.
     *
     * @param ImageInterface $image
     * @param string|null $backgroundColor
     * @param string|null $textColor
     */
    public function __construct(
        ImageInterface $image,
        ?string $backgroundColor = null,
        ?string $textColor = null
    ) {
        $this->image = $image;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
    }

    /**
     * @return ImageInterface
     */
    public function image(): ImageInterface
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Presentation
     */
    public function setImage(ImageInterface $image): Presentation
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function backgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return Presentation
     */
    public function setBackgroundColor(string $backgroundColor): Presentation
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function textColor(): ?string
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     *
     * @return Presentation
     */
    public function setTextColor(string $textColor): Presentation
    {
        $this->textColor = $textColor;

        return $this;
    }
}
