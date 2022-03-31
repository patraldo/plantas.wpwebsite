<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Provider\Image;

interface UrlProviderInterface
{
    /**
     * @param string $imageId An ID/lookupKey corresponding to the requested URL
     *
     * @return string The full URL to the requested image
     */
    public function provide(string $imageId): string;
}
