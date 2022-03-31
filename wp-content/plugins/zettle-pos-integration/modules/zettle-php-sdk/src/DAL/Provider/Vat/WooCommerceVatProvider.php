<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Provider\Vat;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Vat\Vat;
use Inpsyde\Zettle\PhpSdk\DAL\Exception\Vat\VatNotFound;
use WC_Product;
use WC_Tax;

class WooCommerceVatProvider implements VatProvider
{

    /**
     * @var array
     */
    private $location;

    /**
     * @param array $location An array with keys country, state, etc., used for looking up taxes.
     */
    public function __construct(array $location)
    {
        $this->location = $location;
    }

    public function provide(WC_Product $wcProduct): Vat
    {
        $taxClass = $wcProduct->get_tax_class();

        $rates = WC_Tax::find_rates(array_merge(['tax_class' => $taxClass], $this->location));

        if (empty($rates)) {
            throw new VatNotFound("Failed to find tax rates for tax class '$taxClass'.");
        }

        $rate = array_values($rates)[0]['rate'];

        return new Vat($rate);
    }
}
