<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Http\Repositories\ProjectRepository;
use App\Http\Repositories\WeatherRepository;
use App\Http\Services\ProjectService;
use App\Http\Services\WeatherService;
use App\Models\Project;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use App\Models\User;

class WeatherUnitTest extends TestCase
{

    private $service;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->service = new WeatherService(repository: new WeatherRepository());
    }

    public function test_weather()
    {
        $city = "Oeiras";
        Redis::del('weather:' . now()->format('Y-m-d') . ':' . $city);
        $this->service->getCurrentWeather($city);

        $this->assertIsString(Redis::get('weather:' . now()->format('Y-m-d') . ':' . $city));

    }
}
