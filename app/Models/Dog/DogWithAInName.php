<?php

namespace App\Models\Dog;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Builder;

class DogWithAInName extends Dog
{
    protected $table = 'dogs';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('nameFilter', function(Builder $builder) {
            $builder->where('name', 'ILIKE', '%a%');
        });
    }
}
