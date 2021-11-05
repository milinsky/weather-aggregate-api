<?php

declare(strict_types=1);

namespace App\Services\Weather\Providers;

use App\Services\Weather\Contracts\WeatherProviderInterface;
use App\Services\Weather\Dto\AverageWeatherDto;
use App\Services\Weather\Dto\GeoPositionDto;
use App\Services\Weather\Dto\WeatherDto;
use App\Services\Weather\Enum\StatusEnum;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

use function http_build_query;
use function json_decode;
use function array_shift;

class SevenTimerWeatherProvider implements WeatherProviderInterface
{
    private Client $client;
    private string $baseUrl;

    public function __construct(Client $client, array $params)
    {
        $this->client = $client;
        $this->baseUrl = $params['base_url'];
    }

    public function getWeatherByGeoPosition(GeoPositionDto $geoPositionDto): WeatherDto
    {
        $queryString = http_build_query([
            'lon' => $geoPositionDto->longitude,
            'lat' => $geoPositionDto->latitude,
        ]);

        $result = $this->client->get($this->baseUrl . '&' . $queryString);

        $weatherDto = new WeatherDto();

        if ($result->getStatusCode() !== Response::HTTP_OK) {
            $weatherDto->setStatus(StatusEnum::FAIL);
            return $weatherDto;
        }

        $weatherData = json_decode($result->getBody()->getContents());
        $weatherData = array_shift($weatherData->dataseries);

        $weatherDto->setStatus(StatusEnum::SUCCESS);
        $weatherDto->temperature = $weatherData->temp2m;

        return $weatherDto;
    }
}
