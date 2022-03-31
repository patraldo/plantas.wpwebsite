<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\DataProvider\Store;

use Exception;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;
use WC_Tax;

class WooCommerceStoreDataProvider implements StoreDataProvider
{
    /**
     * @var array
     */
    private $location;

    /**
     * @var array
     */
    private $standardTaxRates;

    /**
     * @param array $location An array with keys country, state, etc., used for looking up taxes.
     * @param array $standardTaxRates An array with rates for the standard tax class,
     * using format like in WC_Tax::find_rates.
     */
    public function __construct(array $location, array $standardTaxRates)
    {
        $this->location = $location;
        $this->standardTaxRates = $standardTaxRates;
    }

    /**
     * @inheritDoc
     */
    public function vat(): Vat
    {
        return new Vat($this->vatPercentage());
    }

    /**
     * @inheritDoc
     */
    public function vatPercentage(): float
    {
        if (!$this->taxesEnabled()) {
            return 0.00;
        }

        $baseTaxRates = WC_Tax::get_base_tax_rates();

        if (empty($baseTaxRates)) {
            return 0.00;
        }

        foreach ($baseTaxRates as $baseTaxRate) {
            if (!isset($baseTaxRate['rate'])) {
                continue;
            }

            if ($baseTaxRate['rate']) {
                return $baseTaxRate['rate'];
            }
        }

        return 0.00;
    }

    /**
     * @inheritDoc
     */
    public function currency(): string
    {
        return get_woocommerce_currency();
    }

    /**
     * @return bool
     */
    public function includeTaxes(): bool
    {
        return (bool) wc_prices_include_tax();
    }

    /**
     * @inheritDoc
     */
    public function taxesEnabled(): bool
    {
        return (bool) wc_tax_enabled();
    }

    /**
     * @inheritDoc
     */
    public function taxationType(): string
    {
        throw new Exception('Not implemented');
        return '';
    }

    /**
     * @inheritDoc
     */
    public function country(): string
    {
        return $this->location['country'];
    }

    /**
     * @return array
     */
    public function taxRates(): array
    {
        return $this->standardTaxRates;
    }
}
