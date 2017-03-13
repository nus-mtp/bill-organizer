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
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Record;


class StatisticTest extends TestCase
{
    private $stats;

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

}
