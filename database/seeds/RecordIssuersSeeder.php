<?php

use Illuminate\Database\Seeder;
use RecordIssuerTypesSeeder as Type;

class RecordIssuersSeeder extends Seeder
{

    public static $record_issuers = [
        'Singtel' => Type::BILLORG_ID,
        'SP Services' => Type::BILLORG_ID,
        'DBS' => Type::BANK_ID
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = DB::table('users')->get(['id']);

        foreach (self::$record_issuers as $name => $type) {
            foreach ($user_ids as $user_id) {
                DB::table('record_issuers')->insert([
                    'name' => $name,
                    'type' => $type,
                    'user_id' => $user_id->id
                ]);
            }
        }
    }
}
