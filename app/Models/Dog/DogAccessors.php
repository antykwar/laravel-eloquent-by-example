<?php

namespace App\Models\Dog;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Builder;

class DogAccessors extends Dog
{
    protected $table = 'dogs';

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name . '_' . random_int(1,100);
    }

    public function getIdNameAttribute()
    {
        return $this->attributes['id'] . ':' . $this->attributes['name'];
    }
}
