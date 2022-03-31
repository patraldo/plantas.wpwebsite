<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Http;

use UnexpectedValueException;

class PageReloader implements PageReloaderInterface
{
    /**
     * @inheritDoc
     */
    public function reload(): void
    {
        $key = 'REQUEST_URI';
        if (!is_string($requestUrl = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_URL))) {
            throw new UnexpectedValueException(sprintf('Could not retrieve server variable "%1$s"', $key));
        }

        wp_safe_redirect($requestUrl);
        exit();
    }
}
