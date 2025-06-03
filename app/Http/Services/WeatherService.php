<?php

namespace App\Http\Services;

use App\Entities\WeatherEntity;
use App\Exceptions\WeatherException;
use App\Http\Repositories\WeatherRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function __construct(private WeatherRepository $repository)
    {
    }

    public function getCurrentWeather(string $city): WeatherEntity
    {
        try {
            return $this->repository->find(city: $city);
        } catch (Exception $e) {
        }

        $apiKey = config('services.openweathermap.key');
        $url = 'https://api.openweathermap.org/data/2.5/weather';

        $response = Http::get($url, [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'pt_br',
        ]);

        if ($response->failed()) {
            throw new WeatherException($response->body());
        }

        return $this->repository->create($response->object());
    }
}
