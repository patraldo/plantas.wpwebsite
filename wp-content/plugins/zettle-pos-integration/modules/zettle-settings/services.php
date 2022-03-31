<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Settings;

use Inpsyde\StateMachine\StateMachineInterface;
use Inpsyde\Zettle\Onboarding\OnboardingState;
use Inpsyde\Zettle\Onboarding\Settings\ButtonAction;
use Inpsyde\Zettle\Provider;
use Inpsyde\Zettle\Settings\Provider\SettingsPageProvider;
use Inpsyde\Zettle\Settings\WC\SettingsPage;
use Inpsyde\Zettle\Settings\WC\ZettleIntegration;
use Inpsyde\Zettle\Settings\WC\ZettleIntegrationHeader;
use Psr\Container\ContainerInterface as C;

return [
    'zettle.settings.url' => static function (C $container): string {
        return admin_url('admin.php?page=wc-settings&tab=zettle');
    },
    'zettle.settings.is-integration-page' => static function (C $container): callable {
        return static function (): bool {
            return filter_input(INPUT_GET, 'tab') === 'zettle';
        };
    },
    'zettle.settings.shop-link' => static function (C $container): array {
        return [
            'title' => esc_html__('Order PayPal Zettle Hardware', 'zettle-pos-integration'),
            'url' => esc_url_raw('https://shop.zettle.com/'),
            'icon' => true,
        ];
    },
    'zettle.settings.account.link.api-key-creation-url' => static function (C $container): string {
        return add_query_arg(
            [
                'name' => 'WooCommerce integration',
                'scopes' => implode(
                    '%20',
                    [
                        'READ:FINANCE',
                        'READ:PURCHASE',
                        'READ:USERINFO',
                        'READ:PRODUCT',
                        'WRITE:PRODUCT',
                    ]
                ),
                'utm_source' => 'local_partnership',
                'utm_medium' => 'ecommerce',
                'utm_campaign' => 'woocommerce',
            ],
            'https://my.zettle.com/apps/api-keys'
        );
    },
    'zettle.settings.account.link.signup' => static function (): string {
        $utm = '?utm_source=local_partnership&utm_medium=ecommerce&utm_campaign=woocommerce';

        $urls = [
            'GB' => 'https://www.zettle.com/gb/integrations/e-commerce/woocommerce' . $utm,
            'FR' => 'https://www.zettle.com/fr/integrations/e-commerce/woocommerce' . $utm,
            'SE' => 'https://www.zettle.com/se/integrationer/e-handel/woocommerce' . $utm,
            'NO' => 'https://www.zettle.com/no/integrasjoner/e-handel/woocommerce' . $utm,
            'FI' => 'https://www.zettle.com/fi/integraatiot/verkkokauppa/woocommerce' . $utm,
            'DK' => 'https://www.zettle.com/dk/integrationer/e-commerce/woocommerce' . $utm,
            'NL' => 'https://www.zettle.com/nl/koppelingen/webshop/woocommerce' . $utm,
            'DE' => 'https://www.zettle.com/de/integrationen/e-commerce/woocommerce' . $utm,
            'ES' => 'https://www.zettle.com/es/integraciones/woocommerce' . $utm,
            'IT' => 'https://www.zettle.com/it/integrazioni/woocommerce' . $utm,
            'BR' => 'https://www.zettle.com/br/integracoes/woocommerce' . $utm,
            'MX' => 'https://www.zettle.com/mx/integraciones/woocommerce' . $utm,
            'US' => 'https://www.paypal.com/us/business/pos' . $utm,
        ];
        $location = wc_get_base_location();
        if (isset($urls[$location['country']])) {
            return $urls[$location['country']];
        }

        return $urls['GB'];
    },
    'zettle.settings.account.link.signup-login' => static function (C $container): callable {
        return static function (bool $loggedIn) use ($container): array {
            $accountLink = [
                'title' => esc_html__('Create account now', 'zettle-pos-integration'),
                'url' => esc_url_raw($container->get('zettle.settings.account.link.signup')),
                'icon' => true,
            ];

            if ($loggedIn) {
                return $container->get('zettle.settings.account.link.api')($loggedIn);
            }

            return $accountLink;
        };
    },
    'zettle.settings.account.link.api-key-creation' => static function (C $container): array {
        return [
            'title' => esc_html__('Create API key', 'zettle-pos-integration'),
            'url' => esc_url_raw(
                $container->get('zettle.settings.account.link.api-key-creation-url')
            ),
            'icon' => true,
            'popup' => true,
        ];
    },
    'zettle.settings.account.link.api' => static function (C $container): callable {
        return static function (bool $loggedIn) use ($container): array {
            if ($loggedIn) {
                return [
                    'title' => esc_html__('Manage account', 'zettle-pos-integration'),
                    'url' => esc_url_raw('https://my.zettle.com/'),
                    'icon' => true,
                ];
            }

            return $container->get('zettle.settings.account.link.api-key-creation');
        };
    },
    'zettle.settings.account.link' => static function (C $container): callable {
        return static function (bool $loggedIn) use ($container): array {
            $stateMachine = $container->get('inpsyde.state-machine');
            assert($stateMachine instanceof StateMachineInterface);

            switch ($stateMachine->currentState()->name()) {
                case OnboardingState::WELCOME:
                    return $container->get('zettle.settings.account.link.signup-login')($loggedIn);
                case OnboardingState::API_CREDENTIALS:
                default:
                    return $container->get('zettle.settings.account.link.api')($loggedIn);
            }
        };
    },
    'zettle.settings.account.disconnect' => static function (C $container): array {
        return [
            'title' => __('Disconnect', 'zettle-pos-integration'),
            'name' => 'delete',
            'value' => '',
            'class' => 'btn btn-delete-link',
            'icon' => false,
            'dialog' => [
                'id' => $container->get('zettle.settings.account.disconnect.id'),
                'title' => __(
                    'Are you sure you want to disconnect?',
                    'zettle-pos-integration'
                ),
                'content' => __(
                    '<p>This will remove the connection to PayPal Zettle.</p>
                     <p>Already synced products in PayPal Zettle will not be deleted, but no longer update.</p>',
                    'zettle-pos-integration'
                ),
                'buttons' => [
                    [
                        'action' => ButtonAction::BACK,
                        'label' => __('Cancel', 'zettle-pos-integration'),
                    ],
                    [
                        'action' => ButtonAction::DELETE,
                        'label' => __('Disconnect', 'zettle-pos-integration'),
                    ],
                ],
            ],
        ];
    },
    'zettle.settings.account.disconnect.id' => static function (C $container): string {
        return 'zettleDisconnectModal';
    },
    'zettle.settings.wc-integration.id' => static function (C $container): string {
        return 'zettle';
    },
    'zettle.settings.wc-integration.title' => static function (C $container): string {
        $pluginProperties = $container->get('zettle.plugin.properties');

        return $pluginProperties->shortName();
    },
    'zettle.settings.wc-integration.link' => static function (C $container): callable {
        return static function (bool $loggedIn): array {
            $linkData = [
                'title' => esc_html__('zettle.com', 'zettle-pos-integration'),
                'url' => esc_url_raw('https://zettle.com/'),
                'icon' => true,
            ];

            if ($loggedIn) {
                $linkData['url'] = esc_url_raw('https://my.zettle.com/');
            }

            return $linkData;
        };
    },
    'zettle.settings.wc-integration.description' => static function (C $container): string {
        return __('Point-of-sale system', 'zettle-pos-integration');
    },
    'zettle.settings.wc-integration.header' =>
        static function (C $container): ZettleIntegrationHeader {
            $stateMachine = $container->get('inpsyde.state-machine');
            assert($stateMachine instanceof StateMachineInterface);

            return new ZettleIntegrationHeader(
                $container->get('zettle.settings.account.link'),
                $container->get('zettle.settings.shop-link'),
                $container->get('zettle.settings.wc-integration.link'),
                $container->get('zettle.sdk.dal.provider.organization'),
                $container->get('zettle.settings.account.disconnect'),
                $container->get('zettle.sdk.id-map.product'),
                $container->get('zettle.onboarding.first-import-timestamp'),
                $container->get('zettle.format-timestamp'),
                $container->get('zettle.sync.price-sync-enabled'),
                $container->get('zettle.settings.wc-integration.title'),
                $container->get('zettle.settings.wc-integration.description'),
                $stateMachine->currentState()->name(),
                $container->get('zettle.onboarding.api-auth-check')
            );
        },
    'zettle.settings.wc-integration' => static function (C $container): ZettleIntegration {
        $stateMachine = $container->get('inpsyde.state-machine');
        assert($stateMachine instanceof StateMachineInterface);

        return new ZettleIntegration(
            $container->get('zettle.settings.wc-integration.id'),
            $container->get('zettle.settings.wc-integration.header'),
            $stateMachine->currentState()->name(),
            $container->get('zettle.settings.fields'),
            $container->get('zettle.settings.is-integration-page'),
            $container->get('zettle.settings'),
            ...$container->get('zettle.settings.field-renderers')
        );
    },
    'zettle.settings.is-settings-save-request' => static function (C $container): bool {
        return filter_input(INPUT_POST, 'save') &&
            $container->get('zettle.settings.is-integration-page')();
    },
    'zettle.settings.page.factory' => static function (C $container): callable {
        return static function () use ($container): SettingsPage {
            return new SettingsPage(
                $container->get('zettle.settings.wc-integration'),
                $container->get('zettle.settings.wc-integration.id'),
                $container->get('zettle.settings.wc-integration.title'),
                $container->get('zettle.logger.woocommerce'),
                $container->get('zettle.throw-unhandled-errors')
            );
        };
    },
    'zettle.settings.fields.registry' => static function (): array {
        return [];
    },
    'zettle.settings.fields' => static function (C $container): array {
        /**
         * We're just proxying another entry here. This will make it possible
         * to use separate extensions for registration and filtering of
         * settings fields, which is used by the onboarding module
         */
        return $container->get('zettle.settings.fields.registry');
    },
    'zettle.settings.field-renderers' => static function (): array {
        return [];
    },
    'zettle.settings.provider.settings-page' => static function (C $container): Provider {
        return new SettingsPageProvider(
            $container->get('zettle.settings.page.factory')
        );
    },
    'zettle.settings.provider' => static function (C $container): array {
        return [
            $container->get('zettle.settings.provider.settings-page'),
        ];
    },
];
