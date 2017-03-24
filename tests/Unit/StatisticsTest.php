<?php

namespace Tests\Unit;

use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Support\TestHelperTrait;
use Tests\TestCase;
use \App\Statistics;


class StatisticsTest extends TestCase
{
    private $user;
    private $billOrgs;
    private $stats;
    private $statementIssuers;
    use DatabaseMigrations;
    use DatabaseTransactions;
    use TestHelperTrait;

    public function setUp() {
        parent::setUp();
        $this->prepareDbForTests();
        $this->user = $this->createRandUsers(1)[0];
        $this->billOrgs = $this->createRandBillOrgs($this->user, 3);
        $this->statementIssuers = $this->createRandStatementIssuers($this->user,5);
        $this->stats = new Statistics();
    }

    public function testStatisticsCanBeCreated(){
        self::assertInstanceOf(Statistics:: class, new Statistics());
    }

    public function testItShouldReturnOneAfterAddingABill(){
        $this->createRandBill($this->billOrgs[0]);
        $expected = 1;
        $actual = $this->stats->countBills($this->billOrgs[0]);
        self::assertEquals($expected, $actual);
    }

    public function testAfterAddingTwoRecordsItReturnsTwo(){
        $this->createRandBills($this->billOrgs[0], 2);
        self::assertEquals(2,$this->stats->countBills($this->billOrgs[0]));
    }

    public function testAfterCreatingRandomNumberOfBillsItReturnsCorrectResult(){
        $randNum = random_int(1, 10);
        $this->createRandBills($this->billOrgs[0], $randNum);
        self::assertEquals($randNum , $this->stats->countBills($this->billOrgs[0]));
    }

    public function testItShouldReturnCorrectCountAfterAddingStatementsAndBills(){
        $numOfRandomBillsCreated= count($this->createRandomNumOfBills(1,10));
        $this->createRandomNumberOfStatements();
        self::assertEquals($numOfRandomBillsCreated, $this->stats->countBills($this->billOrgs[0]));
    }

    public function testCanGetTotalNumBillsForCurrMonth(){
        $bills = $this->createRandNumBillForCurrMonth(5,10);
        $expected = count($bills);
        $actual = $this->stats->countCurrMonthBills($this->billOrgs[0]);
        self::assertEquals($expected,$actual);
    }

    public function testCanGetTotalNumBillsForPast6Months(){
        $until = new Carbon("last day of this month");
        $from = $until->copy()->subMonth(6);
        $pastSixMonthBills = $this->createRandBillsForPeriod($from, $until);
        $expected= count($pastSixMonthBills);
        $actual = $this->stats->getNumOfPastMonthsBills($this->billOrgs[0], 6);
        self::assertEquals($expected, $actual);
    }

    public function testCanGetTotalNumBillsForPastNMonths(){
        $n = random_int(1, 12);
        $until = new Carbon("last day of this month");
        $from = $until->copy()->subMonth($n);
        $bills = $this->createRandBillsForPeriod($from, $until);
        $expected = count($bills);
        $actual = $this->stats->getNumOfPastMonthsBills($this->billOrgs[0], $n);
        self::assertEquals($expected, $actual);
    }

    /*
    public function testCanGetBillsTotalAmountForCurrMonth(){
        $currMonthBills = $this->createBillsForCurrentMonth($this->billOrgs[0],10);
        $this->createBillsForPast6MonthsExcludeCurrMonth();
        $expected = $currMonthBills->sum('amount');
        $actual = $this->stats->getBillsTotalAmountForCurrMonth($this->billOrgs[0]);
        self::assertEquals($expected, $actual);
    }
    */

    /*
    public function testCanGetBillsTotalAmountForPastNMonths(){
        $n = random_int(1,12);
        $until = new Carbon("last day of this month");
        $from = $until->copy()->subMonth($n);
        $bills = $this->createRandBillsForPeriod($from, $until);
        $expected = $bills->sum('amount');
        $actual = $this->stats->getBillsTotalAmountForPastMonths($this->billOrgs[0], $n);
        self::assertEquals($expected,$actual);
    }
    */


    /* -----------------  helpers ----------------- */

    private function createRandomNumOfBills($lowerBound, $upperBound) {
        $numOfRandomBills = random_int($lowerBound, $upperBound);
        $bills = $this->createRandBills($this->billOrgs[0], $numOfRandomBills);
        return $bills;
    }

    private function createRandBillsForPeriod($from, $until) {
        $bills = $this->createRandomNumOfBills(15,50);
        $faker = \Faker\Factory::create();
        $bills->each(function($bill) use ($faker , $from, $until){
            $issue_date  =  Carbon::createFromTimestamp($faker->unique()->dateTimeBetween($from, $until)->getTimestamp());
            $bill->issue_date = $issue_date;
            $bill->due_date = $issue_date->copy()->addDay(14);
        });
        $this->billOrgs[0]->records()->saveMany($bills);
        return $bills;
    }

    private function createRandomNumberOfStatements() {
        $numOfRandStatements = random_int(1, 5);
        $statements = $this->createRandStatements($this->statementIssuers[0], $numOfRandStatements);
        return $statements;
    }

    private function createRandNumBillForCurrMonth($lowerBound, $upperBound){
        $numOfRandomBills = random_int($lowerBound, $upperBound);
        $bills = $this->createBillsForCurrentMonth($this->billOrgs[0], $numOfRandomBills);
        return $bills;
    }

    private function createBillsForCurrentMonth($org, $count) {
        $user = $this->user;
        $bills = factory(\App\Record::class,'curr_month_bill', $count)
            ->make()
            ->each(function($bill) use ($user) {
                $bill->user_id = $user->id;
        });
        $org->records()->saveMany($bills);
        return $bills;
    }

    private function createBillsForPast6MonthsExcludeCurrMonth() {
        $until = new Carbon("last day of last month");
        $from = $until->copy()->subMonth(6);
        $this->createRandBillsForPeriod($from, $until);
    }
}
