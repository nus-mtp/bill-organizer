<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(UserRecordIssuersSeeder::class);
        $this->call(RecordTypesSeeder::class);
        $this->call(RecordsSeeder::class);
    }
}
