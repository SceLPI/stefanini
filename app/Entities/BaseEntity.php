<?php

namespace App\Entities;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

abstract class BaseEntity
{
    abstract public function __construct(object $model = new stdClass);
    public static function paginatedEntities(Collection $collection): LengthAwarePaginator
    {
        //REFLECTION/FACTORY TO INSTANTEATE CORRECT CLASS
        $factory = get_called_class();
        $entities = $collection->map(fn(object $object) => (new $factory(model: $object)))->toArray();
        return new LengthAwarePaginator(items: $entities, total: count($entities), perPage: 10);
    }

}
