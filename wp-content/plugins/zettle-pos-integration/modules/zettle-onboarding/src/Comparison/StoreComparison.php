<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Comparison;

use Inpsyde\Zettle\Onboarding\DataProvider\Store\StoreDataProvider;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Organization\TaxationType;

class StoreComparison
{
    /**
     * @var StoreDataProvider
     */
    private $remoteStoreData;

    /**
     * @var StoreDataProvider
     */
    private $localStoreData;

    /**
     * VatCurrencyComparison constructor.
     *
     * @param StoreDataProvider $remoteStoreData
     * @param StoreDataProvider $localStoreData
     */
    public function __construct(
        StoreDataProvider $remoteStoreData,
        StoreDataProvider $localStoreData
    ) {

        $this->remoteStoreData = $remoteStoreData;
        $this->localStoreData = $localStoreData;
    }

    /**
     * @return bool
     */
    public function currency(): bool
    {
        return ($this->remoteStoreData->currency() === $this->localStoreData->currency());
    }

    /**
     * @return bool
     */
    public function includeTaxes(): bool
    {
        return ($this->remoteStoreData->includeTaxes() === $this->localStoreData->includeTaxes());
    }

    public function taxesEnabled(): bool
    {
        return ($this->remoteStoreData->taxesEnabled() === $this->localStoreData->taxesEnabled());
    }

    public function country(): bool
    {
        return ($this->remoteStoreData->country() === $this->localStoreData->country());
    }

    public function taxRatesConfigured(): bool
    {
        return !empty($this->localStoreData->taxRates());
    }

    public function canSyncPrices(): bool
    {
        if (!$this->priceSyncRequiresTaxSync()) {
            return $this->currency();
        }

        return $this->currency() && $this->taxesEnabled() && $this->taxRatesConfigured() && $this->country();
    }

    public function priceSyncRequiresTaxSync(): bool
    {
        return $this->remoteStoreData->taxationType() === TaxationType::VAT;
    }

    /**
     * @return StoreDataProvider
     */
    public function remote(): StoreDataProvider
    {
        return $this->remoteStoreData;
    }

    /**
     * @return StoreDataProvider
     */
    public function local(): StoreDataProvider
    {
        return $this->localStoreData;
    }
}
