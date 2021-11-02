<?php

declare(strict_types=1);

namespace App\Http\Controllers\Weather;

use App\Http\Controllers\Controller;
use App\Services\Weather\GetWeatherActionInterface;
use App\Events\WeatherByCityRequestedEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;

class GetWeatherController extends Controller
{
    private GetWeatherActionInterface $getWeatherAction;
    private Request $request;

    public function __construct(GetWeatherActionInterface $getWeatherAction, Request $request)
    {
        $this->getWeatherAction = $getWeatherAction;
        $this->request = $request;
    }

    public function __invoke(): JsonResponse
    {
        $averageWeatherDto = $this->getWeatherAction->execute(
            $this->request->get('provider'),
            $this->request->get('city')
        );
        !$averageWeatherDto->error ?? Event::dispatch(new WeatherByCityRequestedEvent($averageWeatherDto));
        return response()->json($averageWeatherDto);
    }
}
