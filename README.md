# Weather aggregate api service

## Get started

### Clone this repository:

``git clone https://github.com/milinsky/weather-aggregate-api.git``

### Go to the project directory and run container

`cd weather-aggregate-api.git`

`docker-compose build`

`docker-compose up -d`

For set custom application host use config `/config/nginx/default.conf` (default - localhost)

## Usage

### To get the weather for all plugged providers:

``GET /weather?city={city}``

### To get statistics of popular queries:

Day

``GET /weather/statistic?period=day``


Month

``GET /weather/statistic?period=month``


## Plug additional weather providers

### To plug additional weather providers, you need to implement the interface

``App\Services\Weather\Contracts\WeatherProviderInterface``

and set config in `/src/config/weather.php` in `'providers'` section

Example:

    'YourProviderName' => [
        'class' => YourImplementationClassName::class,
        'params' => [
            'base_url' => 'http://api-url',
        ]
    ],