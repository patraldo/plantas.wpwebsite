<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Variant;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Price\Price;
use Inpsyde\Zettle\PhpSdk\Exception\IdNotFoundException;
use Inpsyde\Zettle\PhpSdk\Map\MapRecordCreator;

class LazyVariant implements VariantInterface, StockQuantityAwareInterface, PriceAwareInterface
{
    use VariantGetterDecoratorTrait;

    /**
     * @var int
     */
    private $localId;

    /**
     * @var VariantTransferInterface
     */
    private $base;

    /**
     * @var MapRecordCreator
     */
    private $recordCreator;

    /**
     * @var bool
     */
    private $persisted;

    public function __construct(
        int $localId,
        VariantTransferInterface $base,
        MapRecordCreator $recordCreator
    ) {
        $this->localId = $localId;
        $this->base = $base;
        $this->recordCreator = $recordCreator;
    }

    public function uuid(): string
    {
        if ($this->persisted) {
            return $this->base->uuid();
        }
        try {
            /**
             * Check idMap for an existing record first.
             * A record might have been already created by a separate identical
             * instance of LazyVariant
             */
            $remoteId = $this->recordCreator->remoteId($this->localId);
            $this->base->setUuid($remoteId);
        } catch (IdNotFoundException $exception) {
            $this->recordCreator->createRecord($this->localId, $this->base->uuid());
        }

        $this->persisted = true;

        return $this->base->uuid();
    }

    public function defaultQuantity(): int
    {
        return $this->base->defaultQuantity();
    }

    public function setDefaultQuantity(int $defaultQuantity): void
    {
        $this->base->setDefaultQuantity($defaultQuantity);
    }

    public function setPrice(?Price $price): void
    {
        $this->base->setPrice($price);
    }

    protected function baseVariant(): VariantInterface
    {
        return $this->base;
    }
}
