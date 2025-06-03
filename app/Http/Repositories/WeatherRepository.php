<?php

namespace App\Http\Repositories;

use App\Entities\WeatherEntity;
use Exception;
use Illuminate\Support\Facades\Redis;

class WeatherRepository
{

    public function find(string $city): WeatherEntity
    {
        $weather = Redis::get('weather:' . now()->format('Y-m-d') . ':' . $city);
        if (!$weather) {
            throw new Exception('Weather not found');
        }
        return new WeatherEntity(redisModel: json_decode($weather));
    }

    public function create(object $weather): WeatherEntity
    {
        $entity = new WeatherEntity(model: $weather);
        Redis::set('weather:' . now()->format('Y-m-d') . ':' . $entity->city_name, json_encode($entity), 60 * 60 * 24 * 2);

        return $entity;
    }

}
