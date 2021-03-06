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

use App\FieldArea;
use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;
use App\Template;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});



$factory->define(RecordIssuer::class, function (Faker\Generator $faker){
   return [
       'name' => $faker->company,
       'type' => RecordIssuerType::random_type(),
       'user_id' => function() {
           return factory(App\User::class)->create()->id;
       }
   ];
});

// precondition: RecordIssuerType must exist
$factory->defineAs(App\RecordIssuer::class, RecordIssuerType::BILLORG_TYPE_NAME , function(Faker\Generator $faker){
    $organization_name = $faker->unique()->randomElement(array(
        "AIA Singapore Pte Ltd", "Boston Business School Pte Ltd",
        "Singtel", "Starhub", "M1 Limited",
        "Tampines Town Council","IRA",
        "UOB Card Centre", "Citibank Credit Cards", "DBS Credit Cards","OCBC Credit Cards","Maybank Credit Cards","HSBC Credit Cards",
        "OCBC Plus", "Maybank Kim Eng Securities Pte Ltd",
        "UOB Car Financing Payment"
    ));
      return [
          'name' => $organization_name,
          'type' => RecordIssuerType::type( RecordIssuerType::BILLORG_TYPE_NAME)->first()->id
      ];
});

// precondition: RecordIssuerType must exist
$factory->defineAs(App\RecordIssuer::class,RecordIssuerType::BANK_STATEMENT_TYPE_NAME , function(Faker\Generator $faker){
    $organization_name = $faker->unique()->randomElement(array(
        "POSB Savings",
        "Citibank Interestplus" ,
        "Maybank Savings" ,
        "UOB Passbook Sasvings",
        "DBS Coporate"
    ));
    return [
        'name' => $organization_name,
        'type' => RecordIssuerType::type(RecordIssuerType::BILLORG_TYPE_NAME)->first()->id
    ];
});

$factory->defineAs(App\Record::class,RecordIssuerType::BILLORG_TYPE_NAME, function(Faker\Generator $faker){
    $issue_date = Carbon::createFromTimestamp($faker->unique()->dateTimeBetween($start_date = '- 5 years', $end_date = 'now')->getTimestamp());
    $due_date = $faker->dateTimeBetween($issue_date, $issue_date->format('y-m-d H:i:s').' + 14 days');
    return [
        'temporary' => false,
        'issue_date' => $issue_date->toDateString(),
        'due_date' => $due_date->format('Y-m-d'),
        'period' => $issue_date->format('Y-m'),
        'amount' => $faker->randomFloat(2, 0, 5000),
        'path_to_file' => 'whatever/tmp/file.pdf',
        'user_id' => $user_id = function() {
            /* Cannot move this outside of the returned array and do $user_id = factory(App\User::class)->create()->id;
             * because that will cause the factory to create a new user, even if user_id is overrode
             */
            return factory(App\User::class)->create()->id;
        },
        'record_issuer_id' => $record_issuer_id = function() use ($user_id) {
            return factory(App\RecordIssuer::class)->create([
                'type' => RecordIssuerType::BILLORG_TYPE_ID,
                'user_id' => $user_id
            ])->id;
        },
        'template_id' => function() use ($record_issuer_id) {
            return factory(Template::class)->create([
                'record_issuer_id' => $record_issuer_id
            ])->id;
        }
    ];
});

$factory->defineAs(App\Record::class,"curr_month_bill", function(Faker\Generator $faker){
    $from = \App\DateHelper::firstDayOfCurrMonth();
    $until = \App\DateHelper::lastDayOfCurrMonth();
    $issue_date = $faker->unique()->dateTimeBetween($from,$until);
    $due_date = $faker->dateTimeBetween($issue_date, $issue_date->format('y-m-d H:i:s').' + 14 days');
    return [
        'temporary' => false,
        'issue_date' => $issue_date->format('Y-m-d'),
        'due_date' => $due_date->format('Y-m-d'),
        'period' => $issue_date->format('Y-m'),
        'amount' => $faker->randomFloat(2, 0, 5000),
        'path_to_file' => 'whatever/tmp/file.pdf',
        'user_id' => $user_id = function() {
            /* Cannot move this outside of the returned array and do $user_id = factory(App\User::class)->create()->id;
             * because that will cause the factory to create a new user, even if user_id is overrode
             */
            return factory(App\User::class)->create()->id;
        },
        'record_issuer_id' => $record_issuer_id = function() use ($user_id) {
            return factory(App\RecordIssuer::class)->create([
                'type' => RecordIssuerType::BILLORG_TYPE_ID,
                'user_id' => $user_id
            ])->id;
        },
        'template_id' => function() use ($record_issuer_id) {
            return factory(Template::class)->create([
                'record_issuer_id' => $record_issuer_id
            ])->id;
        }
    ];
});

/**
 * Be careful, there's a pitfall here.
 * Since in Record, the issue_date, due_date, and period are typecasted to date type
 * The factory returns a Carbon instance instead of the DateString. The returned associative array
 * of this method is only used to create a new instance in the DB
 */
$factory->define(Record::class, function(Faker\Generator $faker){
    $now = Carbon::now();
    $issue_date = Carbon::now()->subDay(random_int(0, 30));
    $period = $issue_date->format('Y-m');
    $amount = round(rand() / getrandmax() * 1000, 2);

    // need to determine issuer type first instead of letting the factory decide because due_date depends on it
    $issuer_type = RecordIssuerType::random_type();
    $is_bill = $issuer_type === RecordIssuerType::BILLORG_TYPE_ID;
    $due_date = $is_bill ? Carbon::now()->addDays(random_int(0, 90))->toDateString() : null;

    return [
        'temporary' => false,
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
        'record_issuer_id' => $record_issuer_id = function() use ($issuer_type, $user_id) {
            return factory(App\RecordIssuer::class)->create([
                'type' => $issuer_type,
                'user_id' => $user_id
            ])->id;
        },
        'template_id' => function() use ($record_issuer_id) {
            return factory(Template::class)->create([
                'record_issuer_id' => $record_issuer_id
            ])->id;
        }
    ];
});


$factory->define(FieldArea::class, function(Generator $faker) {
    return [
        'page' => rand(1, 3),
        'x' => $x = rand(0, Config::get('constants.A4_W_PIXELS') - 1),
        'y' => $y = rand(0, Config::get('constants.A4_H_PIXELS') - 1),
        'w' => rand(0, Config::get('constants.A4_W_PIXELS') - $x),
        'h' => rand(0, Config::get('constants.A4_H_PIXELS') - $y)
    ];
});



$factory->define(Template::class, function(Generator $faker) {
    return [
        'active' => true,
        'record_issuer_id' => function() {
            return factory(RecordIssuer::class)->create()->id;
        },
        'issue_date_area_id' => function() {
            return factory(FieldArea::class)->create()->id;
        },
        'due_date_area_id' => function() {
            return factory(FieldArea::class)->create()->id;
        },
        'period_area_id' => function() {
            return factory(FieldArea::class)->create()->id;
        },
        'amount_area_id' => function() {
            return factory(FieldArea::class)->create()->id;
        }
    ];
});