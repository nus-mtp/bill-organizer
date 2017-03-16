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
use Carbon\Carbon;

use App\RecordIssuerType;

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

$factory->define(App\RecordIssuer::class, function (Faker\Generator $faker){
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

// precondition: RecordIssuerType must exist
$factory->defineAs(App\RecordIssuer::class, RecordIssuerType::BILL_TYPE_NAME , function(Faker\Generator $faker){
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
          'type' => RecordIssuerType::type( RecordIssuerType::BILL_TYPE_NAME)->first()->id
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
        'type' => RecordIssuerType::type(RecordIssuerType::BILL_TYPE_NAME)->first()->id
    ];
});

$factory->defineAs(App\Record::class,RecordIssuerType::BILL_TYPE_NAME, function(Faker\Generator $faker){
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
$factory->define(App\Record::class, function(Faker\Generator $faker){
    $now = Carbon::now();
    $issue_date = Carbon::now()->subDay(random_int(0, 30));
    $period = $issue_date->format('Y-m');
    $amount = round(rand() / getrandmax() * 1000, 2);
    $user_id = factory(App\User::class)->create()->id;
    $record_issuer = factory(App\RecordIssuer::class)->create([
        'user_id' => $user_id
    ]);

    $record_issuer_type = DB::table('record_issuer_types')->find($record_issuer->type);
    $is_bill = $record_issuer_type->type === RecordIssuerType::BILL_TYPE_NAME;
    $due_date = $is_bill ? Carbon::now()->addDays(random_int(0, 90)) : null;

    return [
        'issue_date' => $issue_date->toDateString(),
        'due_date' => $due_date === null ? null : $due_date->toDateString(),
        'period' => $period,
        'amount' => $amount,
        'user_id' => $user_id,
        'path_to_file' => 'whatever/tmp/file.pdf',
        'record_issuer_id' => $record_issuer->id
    ];
});


