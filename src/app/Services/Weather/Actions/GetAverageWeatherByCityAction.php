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
use Illuminate\Support\Facades\Config;

use function array_map;
use function array_sum;
use function count;

class GetAverageWeatherByCityAction implements GetWeatherActionInterface
{
    private WeatherProviderFactory $weatherProviderFactory;
    private GeoProviderInterface $geoProvider;

    public function __construct(WeatherProviderFactory $weatherProviderFactory, GeoProviderInterface $geoProvider)
    {
        $this->weatherProviderFactory = $weatherProviderFactory;
        $this->geoProvider = $geoProvider;
    }

    public function execute(string $providerName, string $city): AverageWeatherDto
    {
        $providers = Config::get('weather.providers');

        $averageWeatherDto = new AverageWeatherDto();

        if (!isset($providers[$providerName])) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::INVALID_PROVIDER;
            return $averageWeatherDto;
        };

        $geoPositionDto = $this->geoProvider->getGeoPositionByCity($city);

        if ($geoPositionDto->status === StatusEnum::FAIL) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::GEO_POSITION_ERROR;
            return $averageWeatherDto;
        }

        $allProvidersWeathers = $this->getWeathersForAllProviders($providers, $geoPositionDto);

        if (!isset($allProvidersWeathers[$providerName])) {
            $averageWeatherDto->status = StatusEnum::FAIL;
            $averageWeatherDto->error = ErrorEnum::INTERNAL_ERROR;
            return $averageWeatherDto;
        }

        $averageWeatherDto->city = $geoPositionDto->city;
        $averageWeatherDto->provider = $providerName;
        $averageWeatherDto->temperature = $allProvidersWeathers[$providerName]->temperature;
        $averageWeatherDto->averageTemperature = $this->calcAverage($allProvidersWeathers);

        return $averageWeatherDto;
    }

    /**
     * @param array[] $providers
     */
    protected function getWeathersForAllProviders(array $providers, GeoPositionDto $geoPositionDto): array
    {
        $allProvidersWeathers = [];
        foreach ($providers as $providerName => $provider) {
            $weatherProvider = $this->weatherProviderFactory->create($provider['class'], $provider['params']);
            $weather = $weatherProvider->getWeatherByGeoPosition($geoPositionDto);
            if ($weather->status === StatusEnum::SUCCESS) {
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
