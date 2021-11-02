<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\WeatherStatistic\AddStatisticDataInterface;
use App\Events\WeatherByCityRequestedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WeatherByCityRequestedEventListener
{
    private AddStatisticDataInterface $addStatisticData;

    public function __construct(AddStatisticDataInterface $addStatisticData)
    {
        $this->addStatisticData = $addStatisticData;
    }

    public function handle(WeatherByCityRequestedEvent $event)
    {
        $this->addStatisticData->execute($event->averageWeatherDto->provider);
    }
}
