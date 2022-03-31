<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Settings\Provider;

use Inpsyde\Zettle\Provider;
use Psr\Container\ContainerInterface as C;

class SettingsPageProvider implements Provider
{

    /**
     * @var callable
     */
    private $pageFactory;

    /**
     * SettingsPageProvider constructor.
     *
     * @param callable $pageFactory
     */
    public function __construct(callable $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function boot(C $container): bool
    {
        add_filter(
            'woocommerce_get_settings_pages',
            function ($settings): array {
                $settings[] = ($this->pageFactory)();

                return $settings;
            }
        );

        return true;
    }
}
