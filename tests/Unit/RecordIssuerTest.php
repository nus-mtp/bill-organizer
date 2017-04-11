<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\TestHelperTrait;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\Template;
use App\User;

class RecordIssuerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use TestHelperTrait;

    const FIRST_EXAMPLE_RECORD_ISSUER_NAME = "Example RecordIssuer Name";

    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->user = $this->generateUserInDb();
        $this->record_issuer = factory(RecordIssuer::class)->create([
            'user_id' => $this->user->id
        ]);
        factory(Record::class)->create([
            'record_issuer_id' => $this->record_issuer->id,
            'user_id' => $this->user->id
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

    public function testGetActiveTemplate()
    {
        $this->assertNotNull($this->record_issuer->active_template());
    }

    public function testCanCreateEmptyRecordIssuerClass()
    {
       $this->assertInstanceOf(RecordIssuer::class, new RecordIssuer());
    }

    public function testCanSaveRecordIssuerInDb() {
        $recordIssuer = $this->makeRecordIssuerWoFactory(self::FIRST_EXAMPLE_RECORD_ISSUER_NAME);
        $this->user->record_issuers()->save($recordIssuer);
        self::assertTrue($recordIssuer->exists);
    }

    public function testCanCreateABillingOrganizationInDbUsingFactory(){
        $org = $this->createRandBillOrg($this->user);
        self::assertTrue($org->exists);
    }

    public function testCanCreateBankStatementOrgTypeInDbUsingFactory(){
        $org = $this->createRandStatementIssuer($this->user);
        self::assertTrue($org->exists);
    }

    public function testCreatedOrganizationShouldBelongToCorrectUser(){
        $recordIssuer = $this->makeRecordIssuerWoFactory(self::FIRST_EXAMPLE_RECORD_ISSUER_NAME);
        $this->user->record_issuers()->save($recordIssuer);
        $expected = $this->user->id;
        $actual = $recordIssuer->user_id;
        self::assertEquals($expected,$actual);
    }

}
