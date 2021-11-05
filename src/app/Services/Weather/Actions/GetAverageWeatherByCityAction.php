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

        $allProvidersWeathers = $this->getWeathersForAllProviders($geoPositionDto);

        $averageWeatherDto->status = StatusEnum::SUCCESS;
        $averageWeatherDto->city = $geoPositionDto->city;
        $averageWeatherDto->providers = $allProvidersWeathers;
        $averageWeatherDto->averageTemperature = $this->calcAverage($allProvidersWeathers);

        return $averageWeatherDto;
    }

    /**
     * @param array[] $providers
     */
    protected function getWeathersForAllProviders(GeoPositionDto $geoPositionDto): array
    {
        $allProvidersWeathers = [];
        foreach ($this->providers as $providerName => $provider) {
            $weatherProvider = $this->weatherProviderFactory->create($provider['class'], $provider['params']);
            $weather = $weatherProvider->getWeatherByGeoPosition($geoPositionDto);
            if ($weather->getStatus() === StatusEnum::SUCCESS) {
                $allProvidersWeathers[$providerName] = $weather;
            }
        }
        return $allProvidersWeathers;
    }

    /**
     * @param WeatherDto[] $allProvidersWeathers
     */
    protected function calcAverage(array $allProvidersWeathers): ?float
    {
        $weatherData = array_map(function ($value) {
            return $value->temperature;
        }, $allProvidersWeathers);

        return $weatherData ? array_sum($weatherData) / count($weatherData) : null;
    }
}
