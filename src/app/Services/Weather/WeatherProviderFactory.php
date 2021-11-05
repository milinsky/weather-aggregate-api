<?php

declare(strict_types=1);

namespace App\Services\Weather;

use App\Services\Weather\Contracts\WeatherProviderInterface;
use Illuminate\Container\Container;

class WeatherProviderFactory
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create(string $className, array $params): WeatherProviderInterface
    {
        return $this->container->make($className, ['params' => $params]);
    }
}

