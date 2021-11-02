<?php

declare(strict_types=1);

namespace App\Http\Controllers\Weather;

use App\Http\Controllers\Controller;
use App\Services\WeatherStatistic\GetStatisticActionInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetStatisticController extends Controller
{
    private GetStatisticActionInterface $getStatisticAction;
    private Request $request;

    public function __construct(GetStatisticActionInterface $getStatisticAction, Request $request)
    {
        $this->getStatisticAction = $getStatisticAction;
        $this->request = $request;
    }

    public function __invoke(): JsonResponse
    {
        $dateTime = new \DateTime('now');
        $dateTime->modify( '-1 ' . $this->request->get('period'))->format('Y-m-d');

        $statisticDto = $this->getStatisticAction->execute($dateTime);

        return response()->json($statisticDto);
    }
}
