<?php

namespace App\Entities;

use stdClass;

class AuthEntity extends BaseEntity
{
    public string $token;
    public string $type;


    public function __construct(object $model = new stdClass)
    {
        $this->token = $model->token;
        $this->type = $model->type;
    }

}
