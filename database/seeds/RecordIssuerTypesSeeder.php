<?php
use Illuminate\Database\Seeder;
class RecordIssuerTypesSeeder extends Seeder
// please practice separation of concern, follow laravel way
// it is impossible to seed('className') individual table for testing if put everything is in one class
{
    const BILLORG_ID = 1;
    const BANK_ID = 2;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('record_issuer_types')->insert([
            'id' => self::BILLORG_ID,
            'type' => 'billing organization',
        ]);
        DB::table('record_issuer_types')->insert([
            'id' => self::BANK_ID,
            'type' => 'bank',
        ]);
    }
}