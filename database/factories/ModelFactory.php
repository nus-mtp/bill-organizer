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
// user Faker/Generator //use with full namespace to avoid platform error
// use App\FieldAreas;
use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;
//use App\Template;

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
    $issue_date = Carbon::createFromTimeStamp($faker->dateTimeBetween($start_date = '- 5 years', $end_date = 'now')->getTimestamp());
    $due_date = $faker->dateTimeBetween($issue_date, $issue_date->format('y-m-d H:i:s').' + 14 days');
    return [
        'issue_date' => $issue_date,
        'due_date' => $due_date ,
        'period' => $issue_date->format('Y-m'),
        'amount' => $faker->randomFloat(2, 0, 5000),
        'path_to_file' => 'whatever/tmp/file.pdf',
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


/**
 * A4 pixels size is 2480x3508 in 300 DPI. Currently, the DPI we're using is supposedly not
 * going to be more than 300 DPI
 */
/*$factory->define(FieldAreas::class, function(Generator $faker) {
   return [
       'page' => rand(),
       'x' => rand(0, 2480),
       'y' => rand(0, 3508),
       'w' => rand(0, 2480),
       'h' => rand(0, 3508)
   ];
});*/



/*$factory->define(Template::class, function(Generator $faker) {
    $record_issuer_id = factory(RecordIssuer::class)->create()->id;
    $issue_date_area_id = factory(FieldAreas::class)->create()->id;
    $due_date_area_id = factory(FieldAreas::class)->create()->id;
    $period_area_id = factory(FieldAreas::class)->create()->id;
    $amount_area_id = factory(FieldAreas::class)->create()->id;

    return [
        'record_issuer_id' => $record_issuer_id,
        'issue_date_area_id' => $issue_date_area_id,
        'due_date_area_id' => $due_date_area_id,
        'period_area_id' => $period_area_id,
        'amount_area_id' => $amount_area_id
    ];
});*/
