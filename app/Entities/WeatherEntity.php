<?php

namespace App\Entities;

class WeatherEntity extends BaseEntity
{
    public string $latitude;
    public string $longitude;
    public string $weather_description;
    public float $temperature;
    public float $temperature_feels_like;
    public float $temperature_min;
    public float $temperature_max;
    public int $pressure;
    public int $humidity;
    public float $wind_speed;
    public float $wind_degree;
    public string $city_name;


    public function __construct(?object $model = null, ?object $redisModel = null)
    {
        if ($redisModel) {
            $this->fromRedis(redisModel: $redisModel);
            return;
        }
        $this->latitude = $model->coord->lon;
        $this->longitude = $model->coord->lat;
        $this->weather_description = $model->weather[0]->description;

        $this->temperature = $model->main->temp;
        $this->temperature_feels_like = $model->main->feels_like;
        $this->temperature_min = $model->main->temp_min;
        $this->temperature_max = $model->main->temp_max;
        $this->pressure = $model->main->pressure;
        $this->humidity = $model->main->humidity;

        $this->wind_speed = $model->wind->speed;
        $this->wind_degree = $model->wind->deg;

        $this->city_name = $model->name;
    }

    public function fromRedis(object $redisModel): void
    {
        $this->latitude = $redisModel->latitude;
        $this->longitude = $redisModel->longitude;
        $this->weather_description = $redisModel->weather_description;

        $this->temperature = $redisModel->temperature;
        $this->temperature_feels_like = $redisModel->temperature_feels_like;
        $this->temperature_min = $redisModel->temperature_min;
        $this->temperature_max = $redisModel->temperature_max;
        $this->pressure = $redisModel->pressure;
        $this->humidity = $redisModel->humidity;

        $this->wind_speed = $redisModel->wind_speed;
        $this->wind_degree = $redisModel->wind_degree;

        $this->city_name = $redisModel->city_name;
    }

}
