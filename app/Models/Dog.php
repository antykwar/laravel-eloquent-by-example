<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    use HasFactory;

    public function oldDogsList()
    {
        return $this->ageGreaterThan(7);
    }

    public function scopeAgeGreaterThan($query, $age)
    {
        return $query->where('age', '>', $age);
    }
}
