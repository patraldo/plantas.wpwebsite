<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync\Job;

use Inpsyde\Queue\Queue\Job\ContextInterface;
use Inpsyde\Queue\Queue\Job\Job;
use Inpsyde\Queue\Queue\Job\JobRepository;
use Inpsyde\WcProductContracts\ProductState;
use Psr\Log\LoggerInterface;

/**
 * Class EnqueueProductSyncJob
 *
 * Fetches a list of all relevant products and inserts a sync job for them
 *
 * @package Inpsyde\Zettle\Sync\Job
 */
class EnqueueProductSyncJob implements Job
{

    const TYPE = 'enqueue-products-to-sync';

    /**
     * @var array
     */
    private $productTypeWhitelist;

    /**
     * @var callable
     */
    private $createJobRecord;

    /**
     * @var callable
     */
    private $productCanSynced;

    /**
     * EnqueueProductSyncJob constructor.
     *
     * @param array $productTypeWhitelist
     * @param callable $createJobRecord
     * @param callable $productCanSynced
     */
    public function __construct(
        array $productTypeWhitelist,
        callable $createJobRecord,
        callable $productCanSynced
    ) {
        $this->productTypeWhitelist = $productTypeWhitelist;
        $this->createJobRecord = $createJobRecord;
        $this->productCanSynced = $productCanSynced;
    }

    /**
     * @inheritDoc
     */
    public function execute(
        ContextInterface $context,
        JobRepository $repository,
        LoggerInterface $logger
    ): bool {
        $products = wc_get_products(
            [
                'return' => 'ids',
                'limit' => -1,
                'status' => ProductState::PUBLISH,
                'type' => $this->productTypeWhitelist,
            ]
        );
        $jobs = [];
        foreach ($products as $product) {
            if (($this->productCanSynced)($product)) {
                $jobs[] = ($this->createJobRecord)(
                    ExportProductJob::TYPE,
                    [
                        'productId' => $product,
                    ]
                );
            }
        }
        /**
         * We should consider using array_chunk here.
         * Massive amounts of products might break a very
         * large insert call
        */
        $repository->add(...$jobs);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function isUnique(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return self::TYPE;
    }
}
