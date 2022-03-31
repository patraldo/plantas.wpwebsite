<?php

namespace Inpsyde\Zettle\PhpSdk\Iterator\Attachment;

use Inpsyde\Zettle\PhpSdk\Iterator\WcProductIterator;
use WC_Product;
use WC_Product_Variable;

/**
 * Returns the featured image attachment ID ($product->get_image_id())
 * of all the product's children.
 * You can use this to fetch all product images of variations of a variable product
 */
class ChildrenImageIterator implements WcProductIterator
{

    /**
     * @var int[]
     */
    private $children = [];

    /**
     * @var int
     */
    private $key = 0;

    /**
     * @var WC_Product
     */
    private $product;

    public function __construct(WC_Product $product)
    {
        $this->product = $product;
        $this->rewind();
    }

    public function current()
    {
        return wc_get_product(current($this->children))->get_image_id();
    }

    public function next()
    {
        next($this->children);
        $this->key++;
    }

    public function key()
    {
        return $this->key;
    }

    public function valid()
    {
        return current($this->children) !== false;
    }

    public function rewind()
    {
        $this->key = 0;
        /**
         * If we know we're dealing with a variable product,
         * use its more specific method that gathers only children that are
         * visible. Makes more sense to use that then.
         */
        if ($this->product instanceof WC_Product_Variable) {
            $this->children = $this->product->get_visible_children();
            return;
        }
        $this->children = $this->product->get_children();
    }

    /**
     * {@inheritDoc}
     */
    public function switchProduct(WC_Product $product): void
    {
        $this->product = $product;
        $this->rewind();
    }
}
