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

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Support\Facades\DB;

use App\FieldAreas;
use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;
use App\Template;


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});



$factory->define(RecordIssuer::class, function (Generator $faker){
   return [
       'name' => $faker->company,
       'type' => RecordIssuerType::random_type(),
       'user_id' => function() {
           return factory(App\User::class)->create()->id;
       }
   ];
});


/**
 * Be careful, there's a pitfall here.
 * Since in Record, the issue_date, due_date, and period are typecasted to date type
 * The factory returns a Carbon instance instead of the DateString. The returned associative array
 * of this method is only used to create a new instance in the DB
 */
$factory->define(Record::class, function(Generator $faker){
    $now = Carbon::now();
    $issue_date = (clone $now)->subDays(random_int(0, 30));
    $period = $issue_date->format('Y-m');
    $amount = round(rand() / getrandmax() * 1000, 2);

    // need to determine issuer type first instead of letting the factory decide because due_date depends on it
    $issuer_type = RecordIssuerType::random_type();
    $is_bill = $issuer_type === RecordIssuerType::BILLORG_TYPE_ID;
    $due_date = $is_bill ? (clone $now)->addDays(random_int(0, 90))->toDateString() : null;

    return [
        'issue_date' => $issue_date->toDateString(),
        'due_date' => $due_date,
        'period' => $period,
        'amount' => $amount,
        'user_id' => $user_id = function() {
            /* Cannot move this outside of the returned array and do $user_id = factory(App\User::class)->create()->id;
             * because that will cause the factory to create a new user, even if user_id is overrode
             */
            return factory(App\User::class)->create()->id;
        },
        'path_to_file' => 'whatever/tmp/file.pdf',
        'record_issuer_id' => function() use ($issuer_type, $user_id) {
            return factory(App\RecordIssuer::class)->create([
                'type' => $issuer_type,
                'user_id' => $user_id
            ]);
        }
    ];
});


$factory->define(FieldAreas::class, function(Generator $faker) {
    return [
        'page' => rand(),
        'x' => rand(0, Config::get('constants.A4_W_PIXELS')),
        'y' => rand(0, Config::get('constants.A4_H_PIXELS')),
        'w' => rand(0, Config::get('constants.A4_W_PIXELS')),
        'h' => rand(0, Config::get('constants.A4_H_PIXELS'))
    ];
});



$factory->define(Template::class, function(Generator $faker) {

    return [
        'record_issuer_id' => function() {
            return factory(RecordIssuer::class)->create()->id;
        },
        'issue_date_area_id' => function() {
            return factory(FieldAreas::class)->create()->id;
        },
        'due_date_area_id' => function() {
            return factory(FieldAreas::class)->create()->id;
        },
        'period_area_id' => function() {
            return factory(FieldAreas::class)->create()->id;
        },
        'amount_area_id' => function() {
            return factory(FieldAreas::class)->create()->id;
        }
    ];
});
