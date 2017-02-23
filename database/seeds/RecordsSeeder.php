<?php

use Illuminate\Database\Seeder;

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = DB::table('users')->get(['id']);
        foreach ($user_ids as $user_id) {
            $user_singtel = DB::table('user_record_issuers')
                ->where('user_id', $user_id->id)
                ->where('name', 'Singtel')
                ->get()->first();
            DB::table('records')->insert([
                'issue_date' => Carbon\Carbon::now(),
                'due_date' => Carbon\Carbon::now(),
                'period' => Carbon\Carbon::now(),
                'amount' => random_int(10, 1000),
                'path_to_file' => '~/anywhere',
                'user_id' => $user_id->id,
                'user_record_issuer_id' => $user_singtel->id
            ]);
        }
    }
}
