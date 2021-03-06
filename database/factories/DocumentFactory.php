<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Assure;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Document;
use Faker\Generator as Faker;

$factory->define(Document::class, function (Faker $faker) {
    return [
        'url' => $faker->imageUrl(600,600),
        
        'documentable_id' => function(){
           return  Contrat::all()->random();
        },
        'documentable_type' => 'App\\Models\\Contrat',
    ];
});
