<?php

declare(strict_types=1);

namespace App\Services\Weather\Providers;

use App\Services\Weather\Contracts\GeoProviderInterface;
use App\Services\Weather\Dto\GeoPositionDto;
use App\Services\Weather\Enum\StatusEnum;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

use function http_build_query;
use function json_decode;
use function array_shift;

class NominatimGeoProvider implements GeoProviderInterface
{
    private Client $client;
    private string $baseUrl;

    public function __construct(Client $client, array $params)
    {
        $this->client = $client;
        $this->baseUrl = $params['base_url'];
    }

    public function getGeoPositionByCity(string $city): GeoPositionDto
    {
        $queryString = http_build_query([
            'q' => $city,
        ]);

        $result = $this->client->get($this->baseUrl . '&' . $queryString);

        $geoPositionDto = new GeoPositionDto();

        $geoPositionData = json_decode($result->getBody()->getContents());

        if (empty($geoPositionData) || $result->getStatusCode() !== Response::HTTP_OK) {
            $geoPositionDto->status = StatusEnum::FAIL;
            return $geoPositionDto;
        }

        $geoPositionData = array_shift($geoPositionData);
        $geoPositionDto->status = StatusEnum::SUCCESS;
        $geoPositionDto->latitude = $geoPositionData->lat;
        $geoPositionDto->longitude = $geoPositionData->lon;
        $geoPositionDto->city = $geoPositionData->display_name;
        $geoPositionDto->placeId = $geoPositionData->place_id;

        return $geoPositionDto;
    }
}
