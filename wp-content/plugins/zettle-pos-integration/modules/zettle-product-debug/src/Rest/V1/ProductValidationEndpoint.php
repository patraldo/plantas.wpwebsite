<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductDebug\Rest\V1;

use Inpsyde\Zettle\Sync\Status\SyncStatusCodes;
use Inpsyde\Zettle\Sync\Validator\ProductValidator;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * This Endpoint validates a product from passed productId and strategy
 * with the given product validator, returns a json response if the product
 * is valid and if not an array of status codes from the validation methods.
 *
 * @see ProductValidator::validate() = Strategy default
 *      This validation method doesn't return the sync status, only the conflicts
 *
 * @see ProductValidator::validateWithLocalDBCheck() = Strategy local-db-check:
 *      returns the sync status by checking this locally, and the conflicts
 */
class ProductValidationEndpoint implements EndpointInterface
{

    public const METHODS = WP_REST_Server::READABLE;
    public const VERSION = 'v1';
    public const ROUTE = '/validate';

    public const STRATEGY_DEFAULT = 'default';
    public const STRATEGY_LOCAL_DB_CHECK = 'local-db-check';

    private $productValidator;

    public function __construct(ProductValidator $productValidator)
    {
        $this->productValidator = $productValidator;
    }

    /**
     * @inheritDoc
     */
    public function methods(): string
    {
        return self::METHODS;
    }

    /**
     * @inheritDoc
     */
    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * @inheritDoc
     */
    public function route(): string
    {
        return self::ROUTE;
    }

    /**
     * @inheritDoc
     */
    public function permissionCallback(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * @inheritDoc
     */
    public function args(): array
    {
        // phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
        // phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
        return [
            'id' => [
                'types' => 'integer',
                'validate_callback' => static function ($value): bool {
                    return is_numeric($value);
                },
                'sanitize_callback' => static function ($value) {
                    return (int) sanitize_text_field($value);
                },
            ],
            'strategy' => [
                'types' => 'string',
                'validate_callback' => static function ($value): bool {
                    return is_string($value);
                },
                'sanitize_callback' => static function ($value) {
                    return (string) sanitize_text_field($value);
                },
            ],
        ];
        // phpcs:enable
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(WP_REST_Request $request): WP_REST_Response
    {
        $productId = (int) $request->get_param('id');
        $strategy = (string) $request->get_param('strategy');
        $strategy = !$strategy ? self::STRATEGY_DEFAULT : $strategy;

        $responseStatus = 200;
        $isValid = true;

        switch ($strategy) {
            case self::STRATEGY_LOCAL_DB_CHECK:
                $result = $this->productValidator->validateWithLocalDBCheck($productId);
                break;
            case self::STRATEGY_DEFAULT:
            default:
                $result = $this->productValidator->validate($productId);
                break;
        }

        if (in_array(SyncStatusCodes::NO_VALID_PRODUCT_ID, $result, true)) {
            $isValid = false;
            $responseStatus = 400;
        }

        if (in_array(SyncStatusCodes::NOT_SYNCED, $result, true)) {
            $isValid = false;
        }

        $responseData = [
            'result' => [
                'valid' => $isValid,
                'error' => $result,
            ],
        ];

        return new WP_REST_Response($responseData, $responseStatus);
    }
}
