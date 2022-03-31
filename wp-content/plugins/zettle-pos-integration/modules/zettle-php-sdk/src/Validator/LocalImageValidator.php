<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Validator;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\LazyImage;
use Inpsyde\Zettle\PhpSdk\DAL\Provider\Image\UrlProviderInterface;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\Image\InvalidImageSizeException;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\Image\UnsupportedImageFileSizeException;
use Inpsyde\Zettle\PhpSdk\Exception\Validator\Image\UnsupportedImageFileTypeException;

/**
 * Technically, Validators are supposed to inspect just the entity itself,
 * but since the ImageInterface is currently all about web-URLs, the transition
 * from a local WordPress image to a remote Zettle image is a bit of an edge-case.
 * Sure, we could download the image from our own WordPress installation to inspect it
 * before syncing, but that would be pretty insane. So we allow ourselves to grab the local image ID
 * and inspect the source image directly.
 */
class LocalImageValidator implements ValidatorInterface
{
    /**
     * @var UrlProviderInterface
     */
    private $filePathProvider;

    /**
     * @var array<int, string>
     */
    protected $supportedImageTypes;

    /**
     * @var int
     */
    protected $minFileSize;

    /**
     * @var int
     */
    protected $maxFileSize;

    /**
     * @var int
     */
    protected $minWidth;

    /**
     * @var int
     */
    protected $minHeight;

    /**
     * @var int
     */
    protected $maxWidth;

    /**
     * @var int
     */
    protected $maxHeight;

    /**
     * @param array<int, string> $supportedImageTypes Key - type of exif_imagetype such as IMAGETYPE_JPEG,
     * value - human-readable name.
     */
    public function __construct(
        UrlProviderInterface $filePathProvider,
        array $supportedImageTypes,
        int $minFileSize,
        int $maxFileSize,
        int $minWidth,
        int $minHeight,
        int $maxWidth,
        int $maxHeight
    ) {
        $this->filePathProvider = $filePathProvider;
        $this->supportedImageTypes = $supportedImageTypes;
        $this->minFileSize = $minFileSize;
        $this->maxFileSize = $maxFileSize;
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * {@inheritDoc}
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    public function accepts($entity): bool
    {
        return $entity instanceof LazyImage;
    }

    /**
     * {@inheritDoc}
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    public function validate($entity): bool
    {
        assert($entity instanceof LazyImage);
        $filePath = $this->filePathProvider->provide((string) $entity->localId());
        $this->validateImageFileSize($filePath);
        $this->validatedImageType($filePath);
        $this->validateImageSize($filePath);

        return true;
    }

    /**
     * @param string $filePath
     *
     * @throws UnsupportedImageFileSizeException
     */
    private function validateImageFileSize(string $filePath): void
    {
        $imageFileSize = filesize($filePath);

        if ($imageFileSize >= $this->maxFileSize) {
            throw new UnsupportedImageFileSizeException(
                sprintf(
                    'Maximum image file size is %d bytes. [%s]',
                    $this->maxFileSize,
                    $filePath
                )
            );
        }

        if ($imageFileSize <= $this->minFileSize) {
            throw new UnsupportedImageFileSizeException(
                sprintf(
                    'Minimum image file size is %d bytes. [%s]',
                    $this->minFileSize,
                    $filePath
                )
            );
        }
    }

    /**
     * @param string $filePath
     *
     * @throws UnsupportedImageFileTypeException
     */
    private function validatedImageType(string $filePath): void
    {
        $type = exif_imagetype($filePath);

        if (!array_key_exists($type, $this->supportedImageTypes)) {
            throw new UnsupportedImageFileTypeException(
                sprintf(
                    'Filetype %s is not supported. Must be one of %s. [%s]',
                    $type,
                    implode(', ', array_unique($this->supportedImageTypes)),
                    $filePath
                )
            );
        }
    }

    /**
     * @param string $filePath
     *
     * @throws InvalidImageSizeException
     */
    private function validateImageSize(string $filePath): void
    {
        [$width, $height] = getimagesize($filePath);

        if ($width < $this->minWidth || $height < $this->minHeight) {
            throw new InvalidImageSizeException(
                sprintf(
                    'Image too small. Must be at least: \'%dx%d\'. [%s]',
                    $this->minWidth,
                    $this->minHeight,
                    $filePath
                )
            );
        }

        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            throw new InvalidImageSizeException(
                sprintf(
                    'Image too large. Must be at most: \'%dx%d\'. [%s]',
                    $this->maxWidth,
                    $this->maxHeight,
                    $filePath
                )
            );
        }
    }
}
