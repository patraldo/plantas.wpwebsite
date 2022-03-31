<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Image;

use Inpsyde\Zettle\PhpSdk\DAL\Connection\ConnectionInterface;

/**
 * Class Image
 *
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors
 *
 * @package Inpsyde\Zettle\PhpSdk\DAL\Entity\Image
 */
class ConcreteImage implements ImageInterface
{

    public const BASE_URL = 'https://image.izettle.com/productimage/';

    /**
     * @var string
     */
    private $identifier;

    public function __construct(
        string $imageLookupKey
    ) {
        $this->identifier = $imageLookupKey;
    }

    /**
     * @return string
     */
    public function imageLookupKey(): string
    {
        return $this->identifier();
    }

    /**
     * Image Dimensions: 560*560px
     *
     * @return string
     */
    public function smallImageUrl(): string
    {
        return self::BASE_URL . 'L/' . $this->imageLookupKey();
    }

    /**
     * Image Dimensions: 2000*2000px
     *
     * @return string
     */
    public function largeImageUrl(): string
    {
        return self::BASE_URL . 'o/' . $this->imageLookupKey();
    }

    public function identifier(): string
    {
        return $this->identifier;
    }
}
