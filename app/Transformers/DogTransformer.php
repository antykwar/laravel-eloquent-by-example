<?php

namespace App\Transformers;

use App\Models\Dog;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class DogTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Dog $dog)
    {
        return [
            'dog' => [
                'name' => $dog->name,
                'age' => $dog->age,
            ],
            'some-random-string' => Str::random(8),
        ];
    }
}
