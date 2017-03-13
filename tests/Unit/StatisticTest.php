<?php
/**
 * Created by PhpStorm.
 * User: kenan
 * Date: 13/3/17
 * Time: 11:59 AM
 */

namespace Tests\Unit;


use App\Statistic;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Record;


class StatisticTest extends TestCase
{
    private $stats;

    use DatabaseMigrations; // migrate schema can create record issuer types, destroy after test

    public function setUp()
    {
        parent::setUp();
        $this->stats = new Statistic();

        // setup stuff you need for testing here, create models, create database if needed (Support/DatabaseMigrations
        // trait do this for u, just import at top)
        // $this->artisan('migrate');
        // $this->artisan('db:seed');
        // Eloquent::unguard(); disables mass assignment protection
    }

    public function testCanGetNumBillsFiledForCurrMonth(){
        // create a bunch of users
        // create some records for each user
            //

        // create 10 bills and 15 bank statements for this month
        // create 20 bills 30 bank statements for past months
        $numBillsFiled = $this->stats->getCurrMonthBillsFiled();

    }
}
