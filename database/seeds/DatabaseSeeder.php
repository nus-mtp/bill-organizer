<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\FieldArea;
use App\Template;
use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;

class DatabaseSeeder extends Seeder
{
    private static $names = ['Charlene Lee', 'Lim Xin Ai', 'Tan Yan Ling', 'Teddy Hartanto', 'Xin Kenan'];
    private static $email_names = ['charlene', 'xinai', 'yanling', 'teddy', 'kenan'];
    private static $record_issuers = [
        'Singtel' => RecordIssuerType::BILLORG_TYPE_ID,
        'SP Services' => RecordIssuerType::BILLORG_TYPE_ID,
        'DBS' => RecordIssuerType::BANK_TYPE_ID
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        self::seedRecordIssuerType();

        // for each user
        for ($i = 0; $i < count(self::$names); $i++) {
            $user = factory(User::class)->create([
                'name' => self::$names[$i],
                'email' => self::$email_names[$i] . '@example.com',
                'password' => bcrypt('password')
            ]);

            // create record issuers
            foreach (self::$record_issuers as $name => $type) {
                $record_issuer = factory(RecordIssuer::class)->create([
                    'name' => $name,
                    'type' => $type,
                    'user_id' => $user->id
                ]);

                // and a record for each record issuer
                self::createNewRecord($user, $record_issuer);
                // and a template
                // self::createNewTemplate($record_issuer);
                if ($name === 'SP Services') {
                    $period_area = self::createNewFieldArea(0, 1018, 32, 101, 39);
                    $issue_date_area = self::createNewFieldArea(0, 990, 28, 136, 44);
                    $amount_area = self::createNewFieldArea(0, 964, 1452, 116, 36);
                    $due_date_area = self::createNewFieldArea(0, 455, 1413, 119, 17);

                    $template = Template::create([
                        'record_issuer_id' => $record_issuer->id,
                        'period_area_id' => $period_area->id,
                        'issue_date_area_id' => $issue_date_area->id,
                        'amount_area_id' => $amount_area->id,
                        'due_date_area_id' => $due_date_area->id
                    ]);
                }
            }
        }
    }

    private static function seedRecordIssuerType() {
        DB::table('record_issuer_types')->insert([
            'id' => RecordIssuerType::BILLORG_TYPE_ID,
            'type' => RecordIssuerType::BILLORG_TYPE_NAME,
        ]);
        DB::table('record_issuer_types')->insert([
            'id' => RecordIssuerType::BANK_TYPE_ID,
            'type' => RecordIssuerType::BANK_STATEMENT_TYPE_NAME
        ]);
    }

    private static function createNewRecord($user, $record_issuer) {
        // need to specify due date because it's related to the type of the record issuer
        $is_bill = $record_issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
        $due_date = $is_bill ? Carbon::now()->addDays(random_int(0, 120))->toDateString() : null;
        $record = factory(Record::class)->create([
            'user_id' => $user->id,
            'record_issuer_id' => $record_issuer->id,
            'due_date' => $due_date
        ]);
        return $record;
    }

    private static function createNewTemplate($record_issuer) {
        $template_data = ['record_issuer_id' => $record_issuer->id];
        $is_bank = $record_issuer->type === RecordIssuerType::BANK_TYPE_ID;
        if ($is_bank) {
            $template_data = array_merge($template_data, ['due_date_area_id' => null]);
        }
        $template = factory(Template::class)->create($template_data);
        return $template;
    }

    private static function createNewFieldArea($page, $x, $y, $w, $h) {
        return FieldArea::create(compact('page', 'x', 'y', 'w', 'h'));
    }

}
