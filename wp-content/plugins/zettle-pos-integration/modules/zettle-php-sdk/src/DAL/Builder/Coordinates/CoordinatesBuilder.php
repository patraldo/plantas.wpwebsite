<?php

# -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Builder\Coordinates;

use Inpsyde\Zettle\PhpSdk\DAL\Entity\Coordinates\Coordinates;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Coordinates\CoordinatesFactory;

final class CoordinatesBuilder implements CoordinatesBuilderInterface
{
    /**
     * @var CoordinatesFactory
     */
    private $coordinatesFactory;

    /**
     * CoordinatesBuilder constructor.
     *
     * @param CoordinatesFactory $coordinatesFactory
     */
    public function __construct(CoordinatesFactory $coordinatesFactory)
    {
        $this->coordinatesFactory = $coordinatesFactory;
    }

    /**
     * @inheritDoc
     */
    public function buildFromArray(array $data): Coordinates
    {
        return $this->build($data);
    }

    /**
     * @inheritDoc
     */
    public function createDataArray(Coordinates $coordinates): array
    {
        return [
           'latitude' => $coordinates->latitude(),
           'longitude' => $coordinates->longitude(),
           'accuracyMeters' => $coordinates->accuracyMeters(),
        ];
    }

    /**
     * @param array $data
     *
     * @return Coordinates
     */
    private function build(array $data): Coordinates
    {
        return $this->coordinatesFactory->create(
            $data['latitude'],
            $data['longitude'],
            $data['accuracyMeters']
        );
    }
}
