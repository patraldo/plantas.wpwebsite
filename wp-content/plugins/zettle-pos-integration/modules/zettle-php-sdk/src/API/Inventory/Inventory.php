<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\API\Inventory;

use Inpsyde\Zettle\PhpSdk\Builder\BuilderInterface;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory\Inventory as InventoryEntity;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Inventory\Transaction;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Location\Location;
use Inpsyde\Zettle\PhpSdk\Exception\BuilderException;
use Inpsyde\Zettle\PhpSdk\Exception\ZettleRestException;
use Inpsyde\Zettle\PhpSdk\RestClientInterface;
use Psr\Http\Message\UriInterface;

class Inventory
{

    private $uri;

    /**
     * @var RestClientInterface
     */
    private $restClient;

    /**
     * @var Locations
     */
    private $locationsClient;

    /**
     * @var Location[]
     */
    private $locations;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var string
     */
    private $integrationUuid;

    public function __construct(
        UriInterface $uri,
        RestClientInterface $restClient,
        Locations $locationsClient,
        BuilderInterface $builder,
        string $integrationUuid
    ) {

        $this->uri = $uri;
        $this->restClient = $restClient;
        $this->locationsClient = $locationsClient;
        $this->builder = $builder;
        $this->integrationUuid = $integrationUuid;
    }

    /**
     * @return Location[]
     * @throws ZettleRestException
     */
    private function locations(): array
    {
        if (!$this->locations) {
            $this->locations = $this->locationsClient->all();
        }

        return $this->locations;
    }

    /**
     * @param Transaction[] $transactions
     *
     * @return InventoryEntity
     *
     * @throws ZettleRestException
     */
    public function performTransactions(Transaction ...$transactions): InventoryEntity
    {
        $url = (string) $this->uri->withPath("/organizations/self/inventory");
        $changes = [];
        foreach ($transactions as $transaction) {
            $changes[] = [
                'productUuid' => $transaction->productUuid(),
                'variantUuid' => $transaction->variantUuid(),
                'fromLocationUuid' => $transaction->fromLocationUuid(),
                'toLocationUuid' => $transaction->toLocationUuid(),
                'change' => $transaction->change(),
            ];
        }
        $payload = [
            'changes' => $changes,
            'externalUuid' => $this->integrationUuid,
        ];
        $result = $this->restClient->put($url, $payload);

        try {
            return $this->builder->build(InventoryEntity::class, $result);
        } catch (BuilderException $exception) {
            throw new ZettleRestException(
                'Could not build Inventory entity after performing transactions',
                0,
                $result,
                $payload,
                $exception
            );
        }
    }

    /**
     * @param string $productUuid
     * @param string $variantUuid
     * @param int $change
     *
     * @return InventoryEntity
     * @throws ZettleRestException
     */
    public function purchase(string $productUuid, string $variantUuid, int $change): InventoryEntity
    {
        $locations = $this->locations();
        $transaction = new Transaction(
            $productUuid,
            $variantUuid,
            $locations['STORE']->uuid(),
            $locations['SOLD']->uuid(),
            $change
        );

        return $this->performTransactions($transaction);
    }

    /**
     * @param string $productUuid
     * @param string $variantUuid
     * @param string $from
     * @param string $to
     * @param int $change
     *
     * @return InventoryEntity
     * @throws ZettleRestException
     */
    public function moveStock(
        string $productUuid,
        string $variantUuid,
        string $from,
        string $to,
        int $change
    ): InventoryEntity {

        $locations = $this->locations();

        $transaction = new Transaction(
            $productUuid,
            $variantUuid,
            $locations[$from]->uuid(),
            $locations[$to]->uuid(),
            $change
        );

        return $this->performTransactions($transaction);
    }

    /**
     * @param string $productUuid
     * @param string $variantUuid
     * @param int $change
     *
     * @return InventoryEntity
     * @throws ZettleRestException
     */
    public function supply(string $productUuid, string $variantUuid, int $change): InventoryEntity
    {
        $locations = $this->locations();
        $transaction = new Transaction(
            $productUuid,
            $variantUuid,
            $locations['SUPPLIER']->uuid(),
            $locations['STORE']->uuid(),
            $change
        );

        return $this->performTransactions($transaction);
    }

    /**
     * @param string $productUuid
     *
     * @return InventoryEntity
     * @throws ZettleRestException
     */
    public function startTracking(string $productUuid): InventoryEntity
    {
        $url = (string) $this->uri->withPath("/organizations/self/inventory");
        $payload = [
            'productUuid' => $productUuid,
        ];

        $result = $this->restClient->post($url, $payload);

        try {
            return $this->builder->build(InventoryEntity::class, $result);
        } catch (BuilderException $exception) {
            throw new ZettleRestException(
                sprintf(
                    'Could not build Inventory entity of product %s after starting inventory tracking',
                    $productUuid
                ),
                0,
                $result,
                $payload,
                $exception
            );
        }
    }

    /**
     * @param string $productUuid
     *
     * @return bool
     * @throws ZettleRestException
     */
    public function stopTracking(string $productUuid): bool
    {
        $url = (string) $this->uri->withPath("/organizations/self/inventory/products/{$productUuid}");
        $payload = [
            'productUuid' => $productUuid,
        ];
        try {
            $this->restClient->delete($url, $payload);
        } catch (ZettleRestException $exception) {
            if ($exception->isType(ZettleRestException::TYPE_PRODUCT_NOT_TRACKED)) {
                return true;
            }
            throw $exception;
        }

        return true;
    }

    /**
     * @param string $productUuid
     * @param string $locationType
     *
     * @return InventoryEntity
     * @throws ZettleRestException
     */
    public function productInventory(string $productUuid, string $locationType): InventoryEntity
    {
        $locations = $this->locations();
        $locationUuid = $locations[$locationType]->uuid();
        $url = (string) $this->uri->withPath(
            "/organizations/self/inventory/locations/{$locationUuid}/products/{$productUuid}"
        );

        $result = $this->restClient->get($url, []);

        try {
            return $this->builder->build(
                InventoryEntity::class,
                $result
            );
        } catch (BuilderException $exception) {
            throw new ZettleRestException(
                sprintf(
                    'Could not build Inventory entity of product %s after fetching it',
                    $productUuid
                ),
                0,
                $result,
                [],
                $exception
            );
        }
    }
}
