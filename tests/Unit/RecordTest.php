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


// unit test template
class RecordTest extends TestCase

{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use TestHelperTrait;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->prepareDbForTests();
        $this->user = $this->generateUserInDb();
        $this->billOrg = $this->createRandBillOrg($this->user);
        $this->statementIssuer = $this->createRandStatementIssuer($this->user);
    }

    public function testCanCreateEmptyRecordClass()
    {
        $this->assertInstanceOf(Record::class, new Record());
    }

    public function testCanCreateARecordInDb()
    {
        $record = $this->makeNonRandomRecordWoFactory($this->billOrg);
        $record->save();
        self::assertTrue($record->exists);
    }

    public function testCanCreateAbillInDbUsingFactory(){
        $bill = $this->createRandBill($this->billOrg);
        self::assertTrue($bill->exists);
    }

    public function testCanCreateABankStatementUsingFactory(){
        $statement = $this->createRandStatement($this->billOrg);
        self::assertTrue($statement->exists);
    }

}
