<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Iterator\Attachment;

use Inpsyde\Zettle\PhpSdk\Iterator\WcProductIterator;
use WC_Product;

/**
 * This class is admittedly a bit weird because it "iterates" over a single value.
 * But this construct allows us to combine it with other Iterators in a general-purpose
 * WcProductIteratorAggregate.
 */
class FeaturedImageIterator implements WcProductIterator
{

    /**
     * @var int The featured image attachment ID
     */
    private $attachmentId;

    /**
     * @var bool Used to check if the attachment ID has already been returned
     */
    private $called = false;

    /**
     * @var WC_Product
     */
    private $product;

    public function __construct(WC_Product $product)
    {
        $this->product = $product;
    }

    public function current()
    {
        $this->called = true;

        return $this->attachmentId;
    }

    public function next()
    {
    }

    public function key()
    {
        return 0;
    }

    public function valid()
    {
        return $this->product && !$this->called and $this->attachmentId;
    }

    public function rewind()
    {
        $this->called = false;
        $this->attachmentId = $this->product->get_image_id();
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
