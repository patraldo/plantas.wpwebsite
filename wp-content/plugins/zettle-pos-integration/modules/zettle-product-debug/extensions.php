<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductDebug;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use Inpsyde\Zettle\ProductDebug\Rest\V1\ProductValidationEndpoint;
use Psr\Container\ContainerInterface as C;

return [
    'inpsyde.assets.registry' => static function (C $container, array $previous): array {
        //phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        $scriptFileName = filter_var(
            wp_unslash($_SERVER['SCRIPT_FILENAME']),
            FILTER_SANITIZE_STRING
        );
        //phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        //@see https://bugs.php.net/bug.php?id=49184
        $currentView = basename(
            $scriptFileName,
            '.php'
        );
        $postType = filter_input(
            INPUT_GET,
            'post_type',
            FILTER_SANITIZE_STRING
        );

        if ($currentView === 'edit' && $postType === 'product') {
            $assetUri = rtrim(plugins_url('/assets/', __DIR__ . '/zettle-pos-integration.php'), '/\\');

            $previous[] = new Style(
                'zettle-product-debug-style',
                "{$assetUri}/product-debug.css",
                Asset::BACKEND
            );

            $previous[] = (
            new Script(
                'zettle-product-debug-modules',
                "{$assetUri}/product-debug-modules.js",
                Asset::BACKEND
            )
            )->withLocalize(
                'zettleProductValidation',
                static function () use ($container): array {
                        return [
                            'url' => $container->get('zettle.product.debug.rest.v1.endpoint.validate.url'),
                            'nonce' => wp_create_nonce('wp_rest'),
                            'status' => $container->get('zettle.sync.status.map'),
                            'requestArguments' => [
                                'strategy' => [
                                    'active' => true,
                                    'value' => ProductValidationEndpoint::STRATEGY_LOCAL_DB_CHECK,
                                ],
                            ],
                        ];
                }
            );
        }

        return $previous;
    },
];
