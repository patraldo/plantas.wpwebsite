<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant;

interface StockQuantityAwareInterface
{
    /**
     * @return int
     */
    public function defaultQuantity(): int;

    /**
     * @param int $defaultQuantity
     *
     * @return void
     */
    public function setDefaultQuantity(int $defaultQuantity): void;
}
