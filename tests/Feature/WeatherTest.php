<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WeatherTest extends TestCase
{

    public function test_01_01_try_weather_request_by_redis_and_web_and_compare(): void
    {
        $webStart = microtime(true);
        $response = $this->get(route('v1.weather.show', ["Teresina"]));
        $webFinish = microtime(true);
        $webElapsedTime = $webFinish - $webStart;

        //TWICE TO ACCESS REDIS ALSO
        $redisStart = microtime(true);
        $response = $this->get(route('v1.weather.show', ["Teresina"]));
        $redisFinish = microtime(true);
        $redisElapsedTime = $redisFinish - $redisStart;

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'latitude',
            'longitude',
            'weather_description',
            'temperature',
            'temperature_feels_like',
            'temperature_min',
            'temperature_max',
            'pressure',
            'humidity',
            'wind_speed',
            'wind_degree',
            'city_name',
        ]);
        assert($redisElapsedTime < $webElapsedTime);
    }

    public function test_01_02_try_non_existing_city(): void
    {
        $response = $this->get(route('v1.weather.show', ["NonExistingCityOnTheWorld"]));

        $response->assertStatus(404);
        $response->assertJson([
            "cod" => "404",
            "message" => "city not found",
        ]);
    }

}
