<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Show the forecast for a country and city',
)]
final class WeatherCityCommand
{
    public function __construct(private readonly WeatherUtil $weatherUtil)
    {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $countryCode,
        #[Argument('City name')] string $city,
    ): int {
        $countryCode = strtoupper($countryCode);
        try {
            $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $city);
        } catch (\OutOfBoundsException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->writeln(sprintf('Location: %s, %s', $city, $countryCode));
        $io->table(['Date', 'Temperature'], array_map(static fn (Measurement $measurement): array => [
            $measurement->getDate()->format('Y-m-d'),
            $measurement->getCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
