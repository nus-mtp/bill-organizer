<?php

use Illuminate\Database\Seeder;

class RecordTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('record_types')->insert([
            'id' => 1,
            'type' => 'bill',
            'amount_field_name' => 'Amount due'
        ]);
        DB::table('record_types')->insert([
            'id' => 2,
            'type' => 'bank statement',
            'amount_field_name' => 'Balance'
        ]);
    }
}
