<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\API\Image;

use Exception;
use Inpsyde\Zettle\PhpSdk\Builder\BuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageCollection;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Image\ImageInterface;
use Inpsyde\Zettle\PhpSdk\Exception\BuilderException;
use Inpsyde\Zettle\PhpSdk\Exception\ZettleRestException;
use Inpsyde\Zettle\PhpSdk\Image\ImageFormat;
use Inpsyde\Zettle\PhpSdk\Image\ImageFormatRetrieverInterface;
use Inpsyde\Zettle\PhpSdk\RestClientInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

class Images
{

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var RestClientInterface
     */
    private $restClient;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /** @var ImageFormatRetrieverInterface */
    private $imageFormatRetriever;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        UriInterface $uri,
        RestClientInterface $restClient,
        BuilderInterface $builder,
        ImageFormatRetrieverInterface $imageFormatRetriever,
        LoggerInterface $logger
    ) {
        $this->uri = $uri;
        $this->restClient = $restClient;
        $this->builder = $builder;
        $this->imageFormatRetriever = $imageFormatRetriever;
        $this->logger = $logger;
    }

    /**
     * @param string $imageUrl
     *
     * @return ImageInterface
     *
     * @throws ZettleRestException|BuilderException
     * @throws UnexpectedValueException Invalid URL given.
     */
    public function create(string $imageUrl): ImageInterface
    {
        $url = (string) $this->uri->withPath('/v2/images/organizations/self/products');
        $payload = $this->generateImagePayload($imageUrl);
        $result = $this->restClient->post($url, $payload);

        return $this->builder->build(ImageInterface::class, [$result['imageLookupKey']]);
    }

    /**
     * @param array $imageUrls
     *
     * @return ImageCollection
     *
     * @throws ZettleRestException|BuilderException
     * @throws UnexpectedValueException Invalid URL given.
     */
    public function bulkCreate(array $imageUrls): ImageCollection
    {
        $url = (string) $this->uri->withPath('/v2/images/organizations/self/products/bulk');
        $payload = $this->generateBulkPayload($imageUrls);
        $result = $this->restClient->post($url, $payload);

        // TODO: Handle Invalid Images at $result['invalid']

        return $this->builder->build(ImageCollection::class, $result['uploaded']);
    }

    private function generateBulkPayload(array $imageUrls): array
    {
        $data = [];

        if (count($imageUrls) === 1) {
            return $this->generateImagePayload(current($imageUrls));
        }

        foreach ($imageUrls as $image) {
            $data['imageUploads'][] = $this->generateImagePayload($image);
        }

        return $data;
    }

    private function generateImagePayload(string $imageUrl): array
    {
        $imageUrl = trim($imageUrl);
        if (empty($imageUrl)) {
            throw new UnexpectedValueException('Image URL is empty.');
        }

        return [
            'imageFormat' => $this->imageFormat($imageUrl),
            'imageData' => null,
            'imageUrl' => $imageUrl,
        ];
    }

    private function imageFormat(string $imageUrl): string
    {
        try {
            return $this->imageFormatRetriever->determineImageFormat($imageUrl);
        } catch (Exception $exception) {
            $this->logger->warning("Failed to determine image format of $imageUrl: {$exception->getMessage()}");

            return ImageFormat::JPEG;
        }
    }
}
