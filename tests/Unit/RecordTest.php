<?php


namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Record;


// unit test template
class RecordTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        // setup stuff you need for testing here, create models, create database if needed (Support/DatabaseMigrations
        // trait do this for u, just import at top)
        // $this->artisan('migrate');
        // $this->artisan('db:seed');
        // Eloquent::unguard(); disables mass assignment protection
    }

    public function tearDown()
    {
        //$this->artisan('migrate:reset');
    }

    // a simple example, all test must be prefix test____ or it won't work
    public function test_it_can_be_created()
    {
        $this->assertInstanceOf(
            Record::class,
            new Record()
        );
    }


}
