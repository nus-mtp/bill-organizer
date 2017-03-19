<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Record;
use App\RecordIssuer;
use App\Template;
use App\TempRecord;
use App\User;

class TempRecordTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $user = factory(User::class)->create();
        $record_issuer = factory(RecordIssuer::class)->create([
            'user_id' => $user->id
        ]);
        $template = factory(Template::class)->create([
            'record_issuer_id' => $record_issuer->id
        ]);
        $this->temp_record = factory(TempRecord::class)->create([
            'user_id' => $user->id,
            'record_issuer_id' => $record_issuer->id,
            'template_id' => $template->id
        ]);
    }

    public function testGetUser()
    {
        $this->assertNotNull($this->temp_record->user);
    }

    public function testGetRecordIssuer()
    {
        $this->assertNotNull($this->temp_record->record_issuer);
    }

    public function testGetIssuerType()
    {
        $this->assertNotNull($this->temp_record->issuer_type);
    }

    public function testGetPages()
    {
        $this->assertNotNull($this->temp_record->pages);
    }

    public function testGetTemplate()
    {
        $this->assertNotNull($this->temp_record->template);
    }

}
