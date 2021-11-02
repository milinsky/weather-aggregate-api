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
        $statisticDto = $this->getStatisticAction->execute($this->request->get('period', ''));
        return response()->json($statisticDto);
    }
}
