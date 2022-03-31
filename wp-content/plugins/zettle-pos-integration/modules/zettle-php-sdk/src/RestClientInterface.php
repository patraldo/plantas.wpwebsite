<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk;

use Inpsyde\Zettle\PhpSdk\Exception\ZettleRestException;

interface RestClientInterface
{

    /**
     * @param string $url
     * @param array $payload
     * @param callable|null $modifyRequest
     *
     * @return array
     *
     * @throws ZettleRestException
     */
    public function get(string $url, array $payload, callable $modifyRequest = null): array;

    /**
     * @param string $url
     * @param array $payload
     * @param callable|null $modifyRequest
     *
     * @return array
     *
     * @throws ZettleRestException
     */
    public function post(string $url, array $payload, callable $modifyRequest = null): array;

    /**
     * @param string $url
     * @param array $payload
     * @param callable|null $modifyRequest
     *
     * @return array
     *
     * @throws ZettleRestException
     */
    public function put(string $url, array $payload, callable $modifyRequest = null): array;

    /**
     * @param string $url
     * @param array $payload
     * @param callable|null $modifyRequest
     *
     * @return array
     *
     * @throws ZettleRestException
     */
    public function delete(string $url, array $payload, callable $modifyRequest = null): array;
}
