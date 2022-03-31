<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Repository\Zettle\Product;

interface ProductRepositoryInterface
{
    /**
     * Get ProductId from Product Uuid
     *
     * @param string $uuid
     *
     * @return int|null
     */
    public function findByUuid(string $uuid): ?int;

    /**
     * Get Uuid from ProductId
     *
     * @param int $productId
     *
     * @return string|null
     */
    public function findById(int $productId): ?string;
}
