<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth\HTTPlug;

use Http\Client\Common\Plugin;
use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This is a little helper to help test error scenarios
 *
 * @see https://en.wikipedia.org/wiki/Chaos_engineering#Chaos_Monkey
 *
 * Class ChaosMonkeyPlugin
 * @package Inpsyde\Zettle\Auth\HTTPlug
 */
class ChaosMonkeyPlugin implements Plugin
{

    /**
     * @var int[]
     */
    private $statusProbability = [];

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * ChaosMonkeyPlugin constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param StreamFactoryInterface $streamFactory
     * @param array $config
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        array $config = []
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $resolver = new OptionsResolver();
        $statusProbabilityKey = 'probability.status';
        $statusProbability = [
            401 => 20,
            500 => 20,
        ];
        $resolver->setDefaults(
            [
                $statusProbabilityKey => static function (OptionsResolver $resolver) use ($statusProbability) {
                    foreach ($statusProbability as $status => $probability) {
                        $resolver->setDefault($status, $probability);
                        $resolver->setAllowedTypes($status, 'int');
                    }
                },
            ]
        );
        $options = $resolver->resolve($config);
        $this->statusProbability = $options[$statusProbabilityKey];
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $error = $this->determineError();
        if (!$error) {
            return $next($request);
        }
        $response = $this->responseFactory->createResponse($error);

        return new FulfilledPromise($response);
    }

    private function determineError(): int
    {
        $error = false;
        $highestP = 0;
        foreach ($this->statusProbability as $status => $probability) {
            $curP = rand(0, 99);
            if ($curP < $probability && $curP > $highestP) {
                $error = $status;
            }
        }

        return $error;
    }
}
