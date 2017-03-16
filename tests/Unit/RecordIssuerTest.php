<?php


namespace Tests\Unit;


use App\RecordIssuer;
use App\RecordIssuerType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Support\TestHelperTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RecordIssuerTest extends TestCase{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use TestHelperTrait;

    const FIRST_EXAMPLE_RECORD_ISSUER_NAME = "Example RecordIssuer Name";

    private $user;

    public function setUp() {
        parent::setUp();
        $this->prepareDbForTests();
        $this->user = $this->generateUsersInDatabase()[0];
    }

    public function testCanCreateEmptyRecordIssuerClass(){
       $this->assertInstanceOf(RecordIssuer::class, new RecordIssuer());
    }

    public function testCanSaveRecordIssuerInDatabase() {
        $recordIssuer = $this->createRecordIssuerModel(self::FIRST_EXAMPLE_RECORD_ISSUER_NAME);
        $this->user->record_issuers()->save($recordIssuer);
        self::assertTrue($recordIssuer->exists);
    }

    public function testCanCreateABillingOrganizationInDbUsingFactory(){
        $org = $this->generateBillOrgs($this->user,1)[0];
        self::assertTrue($org->exists);
    }

    public function testCanCreateBankStatementOrgTypeInDbUsingFactory(){
        $org = $this->generateBanks($this->user, 1)[0];
        self::assertTrue($org->exists);
    }

    public function testCreatedOrganizationBelongToCorrectUser(){
        $recordIssuer = $this->createRecordIssuerModel(self::FIRST_EXAMPLE_RECORD_ISSUER_NAME);
        $this->user->record_issuers()->save($recordIssuer);
        $expected = $this->user->id;
        $actual = $recordIssuer->user_id;
        self::assertEquals($expected,$actual);
    }
}
