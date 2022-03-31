<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync\Job;

use Inpsyde\Queue\ExceptionLoggingTrait;
use Inpsyde\Queue\Queue\Job\ContextInterface;
use Inpsyde\Queue\Queue\Job\Job;
use Inpsyde\Queue\Queue\Job\JobRepository;
use Inpsyde\Zettle\PhpSdk\API\Products\Products;
use Inpsyde\Zettle\PhpSdk\Exception\ZettleRestException;
use Psr\Log\LoggerInterface;

class WipeRemoteProductsJob implements Job
{
    use ExceptionLoggingTrait;

    const TYPE = 'wipe-remote-products';

    /**
     * @var Products
     */
    private $productsClient;

    /**
     * WipeRemoteProductsJob constructor.
     *
     * @param Products $productsClient
     */
    public function __construct(Products $productsClient)
    {
        $this->productsClient = $productsClient;
    }

    /**
     * @param ContextInterface $context
     * @param JobRepository $repository
     * @param LoggerInterface $logger
     *
     * @return bool
     */
    public function execute(
        ContextInterface $context,
        JobRepository $repository,
        LoggerInterface $logger
    ): bool {

        try {
            $products = $this->productsClient->list()->all();
            if (empty($products)) {
                $logger->info("Could not find any remote products to delete");

                return true;
            }
            foreach (array_chunk($products, 50) as $i => $chunk) {
                $logger->info(
                    sprintf(
                        'Deleting product batch %d with %d products',
                        $i,
                        count($chunk)
                    )
                );
                $this->productsClient->deleteBulk($chunk);
            }
        } catch (ZettleRestException $exception) {
            $this->logException($exception, $logger);

            return false;
        }
        $logger->info("Deleted all remote products");

        return true;
    }

    public function isUnique(): bool
    {
        return true;
    }

    public function type(): string
    {
        return self::TYPE;
    }
}
