<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync;

use WC_Product_Variable;
use WC_Product_Variation;

trait VariableInventoryChecker
{

    /**
     * @param WC_Product_Variable $product
     *
     * @return bool
     */
    public function hasStockManagingVariations(WC_Product_Variable $product): bool
    {
        $childrenWithStock = [];

        foreach ($product->get_visible_children() as $variationId) {
            $variation = wc_get_product($variationId);
            assert($variation instanceof WC_Product_Variation);
            if ($variation === null) {
                continue;
            }

            if (!$variation->is_purchasable()) {
                continue;
            }

            if (!$variation->managing_stock() || $variation->managing_stock() === 'parent') {
                continue;
            }

            $childrenWithStock[] = $variationId;
        }

        return !empty($childrenWithStock);
    }
}
