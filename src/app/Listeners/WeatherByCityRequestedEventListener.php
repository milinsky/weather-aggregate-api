<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\Weather\AddStatisticDataActionInterface;
use App\Events\WeatherByCityRequestedEvent;

class WeatherByCityRequestedEventListener
{
    private AddStatisticDataActionInterface $addStatisticData;

    public function __construct(AddStatisticDataActionInterface $addStatisticData)
    {
        $this->addStatisticData = $addStatisticData;
    }

    public function handle(WeatherByCityRequestedEvent $event)
    {
        $this->addStatisticData->execute($event->averageWeatherDto);
    }
}
