<?php

declare(strict_types=1);

namespace App\Services\Weather\Actions;

use App\Services\Weather\Contracts\GeoProviderInterface;
use App\Services\Weather\Dto\GeoPositionDto;
use App\Services\Weather\Dto\WeatherDto;
use App\Services\Weather\Dto\AverageWeatherDto;
use App\Services\Weather\Enum\ErrorEnum;
use App\Services\Weather\Enum\StatusEnum;
use App\Services\Weather\GetWeatherActionInterface;
use App\Services\Weather\WeatherProviderFactory;

use function array_map;
use function array_sum;
use function count;

class GetAverageWeatherByCityAction implements GetWeatherActionInterface
{
    private WeatherProviderFactory $weatherProviderFactory;
    private GeoProviderInterface $geoProvider;
    private array $providers = [];

    public function __construct(
        WeatherProviderFactory $weatherProviderFactory,
        GeoProviderInterface $geoProvider,
        array $providers
    ) {
        $this->weatherProviderFactory = $weatherProviderFactory;
        $this->geoProvider = $geoProvider;
        $this->providers = $providers;
    }

    public function execute(string $city = ''): AverageWeatherDto
    {
        $averageWeatherDto = new AverageWeatherDto();

        if (empty($city)) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::VALUE_FOR_CITY_PASSED_ERROR;
            return $averageWeatherDto;
        }

        $geoPositionDto = $this->geoProvider->getGeoPositionByCity($city);

        if ($geoPositionDto->status === StatusEnum::FAIL) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::GEO_POSITION_ERROR;
            return $averageWeatherDto;
        }

        $weatherForAllProviders = $this->getWeatherForAllProviders($geoPositionDto);

        if (empty($weatherForAllProviders)) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::INTERNAL_ERROR;
            return $averageWeatherDto;
        }

        $averageWeatherDto->status = StatusEnum::SUCCESS;
        $averageWeatherDto->city = $geoPositionDto->city;
        $averageWeatherDto->providers = $weatherForAllProviders;
        $averageWeatherDto->averageTemperature = $this->calcAverage($weatherForAllProviders);

        return $averageWeatherDto;
    }

    protected function getWeatherForAllProviders(GeoPositionDto $geoPositionDto): array
    {
        $weatherForAllProviders = [];
        foreach ($this->providers as $providerName => $provider) {
            $weatherProvider = $this->weatherProviderFactory->create($provider['class'], $provider['params']);
            $weather = $weatherProvider->getWeatherByGeoPosition($geoPositionDto);
            if ($weather->getStatus() === StatusEnum::SUCCESS) {
                $weatherForAllProviders[$providerName] = $weather;
            }
        }
        return $weatherForAllProviders;
    }

    /**
     * @param WeatherDto[] $weatherForAllProviders
     */
    protected function calcAverage(array $weatherForAllProviders): ?float
    {
        $weatherData = array_map(function ($value) {
            return $value->temperature;
        }, $weatherForAllProviders);

        return $weatherData ? array_sum($weatherData) / count($weatherData) : null;
    }
}
