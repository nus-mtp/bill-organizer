<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Record;
use App\RecordIssuer;
use App\Template;
use App\User;

class RecordIssuerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $user = factory(User::class)->create();
        $this->record_issuer = factory(RecordIssuer::class)->create([
            'user_id' => $user->id
        ]);
        factory(Record::class)->create([
            'record_issuer_id' => $this->record_issuer->id,
            'user_id' => $user->id
        ]);
        factory(Template::class)->create([
            'record_issuer_id' => $this->record_issuer->id
        ]);
    }

    public function testGetUser()
    {
        $this->assertNotNull($this->record_issuer->user);
    }

    public function testGetName() {
        $this->assertNotNull($this->record_issuer->name);
    }

    public function testGetRecords()
    {
        $this->assertNotNull($this->record_issuer->records);
    }

    public function testGetIssuerType()
    {
        $this->assertNotNull($this->record_issuer->issuer_type);
    }

    public function testGetTemplates()
    {
        $this->assertNotNull($this->record_issuer->templates);
    }

    public function testGetLatestTemplate()
    {
        $this->assertNotNull($this->record_issuer->latest_template());
    }
}
