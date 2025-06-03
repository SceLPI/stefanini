<?php

namespace App\Http\Controllers;

use App\Exceptions\WeatherException;
use App\Http\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(private WeatherService $service)
    {
    }
    public function show($city)
    {
        try {
            $data = $this->service->getCurrentWeather($city);
            return response()->json($data);
        } catch (WeatherException $weatherException) {
            return response()->json(json_decode($weatherException->getMessage()), 404);
        }
    }
}
