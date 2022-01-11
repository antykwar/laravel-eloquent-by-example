<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function oldDogsList()
    {
        return $this->ageGreaterThan(7);
    }

    public function scopeAgeGreaterThan($query, $age)
    {
        return $query->where('age', '>', $age);
    }
}
