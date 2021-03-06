<?php

declare(strict_types=1);

use App\Services\Weather\Enum\StatusEnum;
use App\Services\Weather\Actions\GetAverageWeatherByCityAction;
use App\Services\Weather\WeatherProviderFactory;
use App\Services\Weather\Contracts\WeatherProviderInterface;
use App\Services\Weather\Contracts\GeoProviderInterface;
use App\Services\Weather\Dto\GeoPositionDto;
use App\Services\Weather\Dto\WeatherDto;

class GetAverageWeatherByCityActionTest extends TestCase
{
    private WeatherProviderFactory $providerFactory;
    private GeoProviderInterface $geoProvider;
    private WeatherProviderInterface $weatherProvider;
    private WeatherDto $weatherDto;
    private GeoPositionDto $geoPositionDto;

    /**
     * @test
     */
    public function executeWithCity(): void
    {
        $action = new GetAverageWeatherByCityAction(
            $this->providerFactory,
            $this->geoProvider,
            $this->getValidProvidersConfigArray()
        );

        $this->geoPositionDto->status = StatusEnum::SUCCESS;
        $this->geoPositionDto->city = 'Vladivostok';
        $this->weatherDto->setStatus(StatusEnum::SUCCESS);
        $this->weatherDto->temperature = 1.1;
        $averageWeatherDto = $action->execute('Vladivostok');
        $this->assertEquals($averageWeatherDto->status, StatusEnum::SUCCESS);
        $this->assertEquals($averageWeatherDto->averageTemperature, 1.1);
    }

    /**
     * @test
     */
    public function executeWithoutCity(): void
    {
        $action = new GetAverageWeatherByCityAction(
            $this->providerFactory,
            $this->geoProvider,
            $this->getValidProvidersConfigArray()
        );

        $averageWeatherDto = $action->execute('');
        $this->assertEquals($averageWeatherDto->status, StatusEnum::FAIL);
    }

    /**
     * @test
     */
    public function executeWithIndefiniteGeoPosition(): void
    {
        $action = new GetAverageWeatherByCityAction(
            $this->providerFactory,
            $this->geoProvider,
            $this->getValidProvidersConfigArray()
        );

        $this->geoPositionDto->status = StatusEnum::FAIL;
        $averageWeatherDto = $action->execute('qweqweqwe');
        $this->assertEquals($averageWeatherDto->status, StatusEnum::FAIL);
    }

    private function getValidProvidersConfigArray(): array
    {
        return [
            'Provider1' => [
                'class' => 'ClassName1',
                'params' => [
                    'base_url' => 'https://url1',
                ]
            ],
            'Provider2' => [
                'class' => 'ClassName2',
                'params' => [
                    'base_url' => 'http://url2',
                ]
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->weatherDto = new WeatherDto();
        $this->weatherProvider = $this->createMock(WeatherProviderInterface::class);
        $this->weatherProvider->method('getWeatherByGeoPosition')->willReturn($this->weatherDto);

        $this->geoPositionDto = new GeoPositionDto();

        $this->geoProvider = $this->createMock(GeoProviderInterface::class);
        $this->geoProvider->method('getGeoPositionByCity')->willReturn($this->geoPositionDto);

        $this->providerFactory = $this->createMock(WeatherProviderFactory::class);
        $this->providerFactory->method('create')->willReturn($this->weatherProvider);
    }
}
