<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductSettings\Handler;

use Inpsyde\Queue\Queue\Job\JobRepository;
use Inpsyde\WcProductContracts\ProductType;
use Inpsyde\Zettle\PhpSdk\Repository\WooCommerce\Product\ProductRepositoryInterface;
use Inpsyde\Zettle\ProductSettings\Components\TermManager;
use Inpsyde\Zettle\Sync\Job\DeleteProductJob;
use Inpsyde\Zettle\Sync\Job\ExportProductJob;
use Inpsyde\Zettle\Sync\Job\SetInventoryTrackingJob;
use Inpsyde\Zettle\Sync\Job\UnlinkProductJob;

class ProductExcludeHandler
{

    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    /**
     * @var TermManager
     */
    private $termManager;

    /**
     * @var callable
     */
    private $createJob;

    /**
     * @var JobRepository
     */
    private $jobRepository;

    /**
     * ProductExcludeHandler constructor.
     *
     * @param ProductRepositoryInterface $repository
     * @param TermManager $termManager
     * @param JobRepository $jobRepository
     * @param callable $createJob
     */
    public function __construct(
        ProductRepositoryInterface $repository,
        TermManager $termManager,
        JobRepository $jobRepository,
        callable $createJob
    ) {

        $this->repository = $repository;
        $this->termManager = $termManager;
        $this->jobRepository = $jobRepository;
        $this->createJob = $createJob;
    }

    /**
     * Check if a Product is valid and doesn't have the zettle_exclude term related
     *
     * @param int $objectId
     * @param string $taxonomy
     * @param int ...$termIds
     *
     * @return bool
     */
    public function isIncludable(int $objectId, string $taxonomy, int ...$termIds): bool
    {
        if (!$this->acceptsProduct($objectId)) {
            return false;
        }

        if (!$this->hasTerm($taxonomy, ...$termIds)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the Product is valid and has the zettle_excluded term related
     *
     * @param int $objectId
     * @param string $taxonomy
     * @param int ...$termIds
     *
     * @return bool
     */
    public function isExcludable(int $objectId, string $taxonomy, int ...$termIds): bool
    {
        if (!$this->acceptsProduct($objectId)) {
            return false;
        }

        if ($this->hasTerm($taxonomy, ...$termIds)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $objectId
     *
     * @return bool
     */
    public function acceptsProduct(int $objectId): bool
    {
        $product = $this->repository->findById($objectId);

        if ($product === null) {
            return false;
        }

        if ($product->is_type(ProductType::VARIATION)) {
            return false;
        }

        return true;
    }

    /**
     * Handle a Product that will be excluded - term was added
     *
     * @param int $productId
     */
    public function excludeProduct(int $productId): void
    {
        $product = $this->repository->findById($productId);

        if ($product === null) {
            return;
        }

        $jobs = [];

        if ($product->managing_stock()) {
            $jobs[] = ($this->createJob)(
                SetInventoryTrackingJob::TYPE,
                [
                    'productId' => $productId,
                    'state' => false,
                ]
            );
        }

        $jobs[] = ($this->createJob)(
            DeleteProductJob::TYPE,
            [
                'productId' => $productId,
            ]
        );

        $jobs[] = ($this->createJob)(
            UnlinkProductJob::TYPE,
            [
                'localId' => $productId,
            ]
        );

        $this->jobRepository->add(
            ...$jobs
        );
    }

    /**
     * Handle a Product that will be included - term was removed
     *
     * @param int $productId
     */
    public function includeProduct(int $productId): void
    {
        $product = $this->repository->findById($productId);

        if ($product === null) {
            return;
        }

        $this->jobRepository->add(
            ($this->createJob)(
                ExportProductJob::TYPE,
                [
                    'productId' => $productId,
                ]
            )
        );
    }

    /**
     * @param string $taxonomy
     * @param int ...$termIds
     *
     * @return bool
     */
    protected function hasTerm(string $taxonomy, int ...$termIds): bool
    {
        if (!in_array($this->termManager->id(), $termIds, true)) {
            return false;
        }

        if ($taxonomy !== $this->termManager->taxonomy()) {
            return false;
        }

        return true;
    }
}
