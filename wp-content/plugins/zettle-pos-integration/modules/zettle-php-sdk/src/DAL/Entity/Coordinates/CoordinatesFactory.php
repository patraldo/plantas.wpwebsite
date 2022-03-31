<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Coordinates;

use Inpsyde\Zettle\PhpSdk\DAL\Exception\Coordinates\InvalidLatitudeException;
use Inpsyde\Zettle\PhpSdk\DAL\Exception\Coordinates\InvalidLongitudeException;
use Inpsyde\Zettle\PhpSdk\DAL\Exception\ValidatorException;
use Inpsyde\Zettle\PhpSdk\DAL\Validator\Coordinates\CoordinatesValidator;

class CoordinatesFactory
{
    /**
     * @var CoordinatesValidator
     */
    private $coordinatesValidator;

    /**
     * CoordinatesFactory constructor.
     *
     * @param CoordinatesValidator $coordinatesValidator
     */
    public function __construct(CoordinatesValidator $coordinatesValidator)
    {
        $this->coordinatesValidator = $coordinatesValidator;
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @param string $accuracyMeters
     *
     * @return Coordinates
     *
     * @throws ValidatorException
     * @throws InvalidLatitudeException
     * @throws InvalidLongitudeException
     */
    public function create(
        string $latitude,
        string $longitude,
        string $accuracyMeters
    ): Coordinates {
        $this->coordinatesValidator->validate($latitude, $longitude);

        return new Coordinates(
            (float) $latitude,
            (float) $longitude,
            (float) $accuracyMeters
        );
    }
}
