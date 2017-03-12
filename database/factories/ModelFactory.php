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

use Illuminate\Support\Facades\DB;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});



$factory->define(App\UserRecordIssuer::class, function (Faker\Generator $faker){
   return [
       'name' => $faker->company,
       'type' => function() {
           $record_issuer_types = DB::table('record_issuer_types')->pluck('id')->toArray();
           $rand_index = array_rand($record_issuer_types);
           return $record_issuer_types[$rand_index];
       },
       'user_id' => function() {
           return factory(App\User::class)->create()->id;
       }
   ];
});



$factory->define(App\Record::class, function(Faker\Generator $faker){
    return [
        'issue_date' => $issue_date = $faker->dateTimeBetween($start_date = '- 5 years', $end_date = 'now'),
        'due_date' => $due_date = $faker->randomElements([null, $faker->dateTimeBetween($start_date, $start_date->format('y-m-d H:i:s').' + 14 days')]),
        'period' => $issue_date->month,
        'amount' => $faker->randomFloat(2, 0, 5000),
        'user_id'=> function(){
            return factory(App\User::class)->make()->id;
        },
        'path_to_file'=> function(array $record) use ($faker){
            return $faker->imageUrl();
        },
        'user_record_issuer_id'=> function(){
            return factory(App\UserRecordIssuer::class)->id;
        }
    ];
});


