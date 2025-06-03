<?php

namespace App\Entities;

class UserEntity extends BaseEntity
{
    public int $id;
    public string $name;
    public string $email;
    private string $password;
    public AuthEntity $auth;

    public function __get($param)
    {
        //HIDDING FROM SERIALIZATION
        if ($param) {
            return $this->password;
        }
    }

    public function __construct(object $model = new User())
    {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->email = $model->email;
        $this->password = $model->password;
        $this->auth = new AuthEntity($model->auth);

    }

}
