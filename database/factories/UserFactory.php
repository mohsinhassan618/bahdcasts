<?php

use Faker\Generator as Faker;
use Bahdcasts\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Bahdcasts\User::class, function (Faker $faker) {

    $name = $faker->name;
    return [
        'name' => $name,
        'username' => str_slug($name),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirm_token'  => str_random(10)
    ];
});
