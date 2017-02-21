<?php

use Illuminate\Database\Seeder;

class UserRecordIssuersSeeder extends Seeder
{

    public static $names = ['Charlene Lee', 'Lim Xin Ai', 'Tan Yan Ling', 'Teddy Hartanto', 'Xin Kenan'];
    public static $email_names = ['charlene', 'xinai', 'yanling', 'teddy', 'kenan'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < count(self::$names); $i++) {
            DB::table('users')->insert([
                'name' => self::$names[$i],
                'email' => self::$email_names[$i] . '@example.com',
                'password' => bcrypt('password')
            ]);
        }
    }
}
