<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk;

use Inpsyde\Zettle\Auth\Exception\AuthenticationException;
use Inpsyde\Zettle\PhpSdk\Exception\ZettleRestException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

class Psr18RestClient implements RestClientInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var callable[]
     */
    private $listeners;

    /**
     * Psr18RestClient constructor.
     *
     * @param LoggerInterface $logger
     * @param ClientInterface $client
     * @param UriFactoryInterface $uriFactory
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface $streamFactory
     * @param callable[] $listeners
     */
    public function __construct(
        LoggerInterface $logger,
        ClientInterface $client,
        UriFactoryInterface $uriFactory,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        callable ...$listeners
    ) {

        $this->logger = $logger;
        $this->client = $client;
        $this->uriFactory = $uriFactory;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->listeners = $listeners;
    }

    /**
     * @inheritDoc
     */
    public function post(string $url, array $payload, callable $modifyRequest = null): array
    {
        return $this->sendRequest('POST', $url, $payload, $modifyRequest);
    }

    /**
     * @inheritDoc
     */
    public function get(string $url, array $payload, callable $modifyRequest = null): array
    {
        return $this->sendRequest('GET', $url, $payload, $modifyRequest);
    }

    /**
     * @inheritDoc
     */
    public function put(string $url, array $payload, callable $modifyRequest = null): array
    {
        return $this->sendRequest(
            'PUT',
            $url,
            $payload,
            $modifyRequest
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(string $url, array $payload, callable $modifyRequest = null): array
    {
        return $this->sendRequest('DELETE', $url, $payload, $modifyRequest);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $payload
     * @param callable|null $modifyRequest
     *
     * @return array
     *
     * @throws ZettleRestException
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private function sendRequest(
        string $method,
        string $url,
        array $payload,
        callable $modifyRequest = null
    ): array {
        $body = json_encode($payload);
        if (in_array($method, ['GET', 'HEAD', 'TRACE'], true)) {
            $body = '';
        }

        $request = $this->requestFactory->createRequest($method, $url)
            ->withBody($this->streamFactory->createStream($body))
            ->withHeader('Content-Type', 'application/json');

        $request = $modifyRequest
            ? $modifyRequest($request)
            : $request;

        try {
            $response = $this->client->sendRequest($request);
            $status = $response->getStatusCode();
        } catch (ClientExceptionInterface $exception) {
            $data = [];
            if ($exception instanceof AuthenticationException) {
                $data = [
                    'errorType' => ZettleRestException::TYPE_UNAUTHENTICATED,
                ];
            }
            throw new ZettleRestException(
                $exception->getMessage(),
                $exception->getCode(),
                $data,
                $payload,
                $exception
            );
        }

        array_walk(
            $this->listeners,
            static function (callable $listener) use ($response, $request) {
                $listener($response, $request);
            }
        );

        $body = $response->getBody();
        $body->rewind();

        $contents = $body->getContents();

        $json = json_decode($contents, true) ?? [];

        if (!($status >= 200 && $status < 400)) {
            $message = "Got status {$status} when sending {$method} request to {$url}";
            if ($status === 401 || $status === 403) {
                $json = [
                    'errorType' => ZettleRestException::TYPE_UNAUTHENTICATED,
                ];
            }
            throw new ZettleRestException(
                $message,
                $status,
                (array) $json,
                $payload
            );
        }

        return $json;
    }
}
