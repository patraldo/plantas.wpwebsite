<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\DataProvider\Store;

use Exception;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;

interface StoreDataProvider
{

    /**
     * @return Vat
     * @throws Exception
     */
    public function vat(): Vat;

    /**
     * @return float
     * @throws Exception
     */
    public function vatPercentage(): float;

    /**
     * @return string
     * @throws Exception
     */
    public function currency(): string;

    /**
     * @return bool
     * @throws Exception
     */
    public function includeTaxes(): bool;

    /**
     * @return bool
     * @throws Exception
     */
    public function taxesEnabled(): bool;

    /**
     * @return string
     * @throws Exception
     */
    public function taxationType(): string;

    /**
     * @return string
     * @throws Exception
     */
    public function country(): string;

    /**
     * @return array
     * @throws Exception
     */
    public function taxRates(): array;
}
