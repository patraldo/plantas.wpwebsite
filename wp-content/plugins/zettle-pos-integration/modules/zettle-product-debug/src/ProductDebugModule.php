<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductDebug;

use Dhii\Container\ServiceProvider;
use Dhii\Modular\Module\ModuleInterface;
use Exception;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface as C;
use WP_CLI;

class ProductDebugModule implements ModuleInterface
{

    /**
     * @inheritDoc
     */
    public function setup(): ServiceProviderInterface
    {
        return new ServiceProvider(
            require __DIR__ . '/../services.php',
            require __DIR__ . '/../extensions.php'
        );
    }

    /**
     * @inheritDoc
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function run(C $container): void
    {
        add_action(
            'rest_api_init',
            static function () use ($container) {
                $validateEndpoint = $container->get('zettle.product.debug.rest.v1.endpoint.validate');

                register_rest_route(
                    $container->get('zettle.product.debug.rest.namespace'),
                    $validateEndpoint->route(),
                    [
                        'methods' => $validateEndpoint->methods(),
                        'callback' => [$validateEndpoint, 'handleRequest'],
                        'permission_callback' => [$validateEndpoint, 'permissionCallback'],
                        'args' => $validateEndpoint->args(),
                    ]
                );
            }
        );

        $customColumn = $container->get('zettle.product.debug.listing.custom-column');

        add_filter(
            'manage_edit-product_columns',
            static function ($columns) use ($customColumn) {
                if (!is_admin()) {
                    return $columns;
                }

                return $customColumn->add($columns);
            }
        );

        add_action(
            'manage_posts_custom_column',
            static function ($columnName) use ($customColumn) {
                if (!is_admin()) {
                    return;
                }

                $content = $customColumn->renderContent($columnName, (int) get_the_ID());

                if (!empty($content)) {
                    echo wp_kses_post($content);
                }
            },
            10,
            3
        );

        if (defined('WP_CLI') && WP_CLI) {
            try {
                WP_CLI::add_command(
                    "zettle products",
                    $container->get('zettle.product.debug.cli.products')
                );
            } catch (Exception $exception) {
            }
        }
    }
}
