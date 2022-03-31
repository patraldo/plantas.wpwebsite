<?php

declare(strict_types=1);

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

namespace Inpsyde\Zettle\PhpSdk\API\Listener;

/**
 * Interface ApiRestListener
 *
 * Used to scope API Rest Client Listener that will be enqueued after a request to the API
 *
 * @package Inpsyde\Zettle\PhpSdk\API\Listener
 */
interface ApiRestListener
{
    public const CREATE = 'create';

    public const READ = 'read';

    public const UPDATE = 'update';

    public const DELETE = 'delete';

    /**
     * @param string $operation
     * @param $payload
     * @param bool $success
     *
     * @return bool
     */
    public function accepts(string $operation, $payload, bool $success): bool;

    /**
     * @param $payload
     *
     * @return bool
     */
    public function execute($payload): bool;
}
