<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

final class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly MeasurementRepository $measurementRepository,
    ) {
    }

    /** @return Measurement[] */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementRepository->findByLocation($location);
    }

    /**
     * @return Measurement[]
     * @throws \OutOfBoundsException If the location does not exist.
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if ($location === null) {
            throw new \OutOfBoundsException(sprintf('Location "%s, %s" was not found.', $city, $countryCode));
        }

        return $this->getWeatherForLocation($location);
    }
}
