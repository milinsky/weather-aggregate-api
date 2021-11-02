<?php

namespace App\Providers;

use App\Services\Weather\Providers\NominatomGeoProvider;
use App\Services\Weather\Contracts\GeoProviderInterface;
use App\Services\Weather\GetWeatherActionInterface;
use App\Services\Weather\Actions\GetAverageWeatherByCityAction;
use App\Services\WeatherStatistic\AddStatisticDataInterface;
use App\Services\WeatherStatistic\GetStatisticActionInterface;
use App\Services\WeatherStatistic\Actions\AddRequestDataWeatherAction;
use App\Services\WeatherStatistic\Actions\GetStatisticPopularWeatherRequestsAction;
use App\Http\Controllers\Weather\GetWeatherController;
use App\Http\Controllers\Weather\GetStatisticController;
use App\Listeners\WeatherByCityRequestedEventListener;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(GetWeatherController::class)
                  ->needs(GetWeatherActionInterface::class)
                  ->give(function ($app) {
                      return $app->make(GetAverageWeatherByCityAction::class);
              });

        $this->app->when(GetStatisticController::class)
                  ->needs(GetStatisticActionInterface::class)
                  ->give(function ($app) {
                      return $app->make(GetStatisticPopularWeatherRequestsAction::class);
            });

        $this->app->when(GetAverageWeatherByCityAction::class)
                  ->needs(GeoProviderInterface::class)
                  ->give(function ($app) {
                      return $app->make(NominatomGeoProvider::class, Config::get('weather.geo.Nominatim'));
            });

        $this->app->when(WeatherByCityRequestedEventListener::class)
                  ->needs(AddStatisticDataInterface::class)
                  ->give(function ($app) {
                      return $app->make(AddRequestDataWeatherAction::class);
            });
    }
}
