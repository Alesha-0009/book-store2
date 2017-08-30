<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Book::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(12),
        'abs' => $faker->unique()->randomNumber(9),
    ];
});
$factory->define(App\Models\Author::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name(),
        'surname' => $faker->lastName(),
    ];
});
$factory->define(App\Models\Genre::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(12),
    ];
});
$factory->define(App\Models\Book\BookAuthor::class, function (Faker\Generator $faker) {

    return [
        'author_id' =>  function () {
            return factory(App\Models\Author::class)->create()->id;
        },
        'book_id'   =>  function () {
            return factory(App\Models\Book::class)->create()->id;
        }
    ];
});
$factory->define(App\Models\Book\BookGenre::class, function (Faker\Generator $faker) {

    return [
        'genre_id' =>  function () {
            return factory(App\Models\Genre::class)->create()->id;
        },
        'book_id'   =>  function () {
            $count = \Illuminate\Support\Facades\DB::table('books')->count();

            return \Illuminate\Support\Facades\DB::table('books')->find(rand(1,$count))->id;
        }
    ];
});
