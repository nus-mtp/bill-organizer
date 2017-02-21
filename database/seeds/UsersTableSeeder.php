<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public static $record_issuers = ['Singtel', 'SP Services', 'DBS'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = DB::table('users')->get(['id']);

        for ($i = 0; $i < count(self::$record_issuers); $i++) {
            foreach ($user_ids as $user_id) {
                DB::table('user_record_issuers')->insert([
                    'name' => self::$record_issuers[$i],
                    'user_id' => $user_id->id
                ]);
            }
        }
    }
}
