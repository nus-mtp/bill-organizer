<?php


namespace Tests\Unit;
use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Support\TestHelperTrait;
use Tests\TestCase;
use \App\Statistics;


// unit test template
class RecordTest extends TestCase

{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use TestHelperTrait;

    public function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        // setup stuff you need for testing here, create models, create database if needed (Support/DatabaseMigrations
        // trait do this for u, just import at top)
        // $this->artisan('migrate');
        // $this->artisan('db:seed');
        // Eloquent::unguard(); disables mass assignment protection
    }

    public function tearDown()
    {
    }

    public function testCanCreateARecordClass()
    {
        $this->assertInstanceOf(Record::class, new Record());
    }

    public function canCreateABillRecord(){

    }


}
