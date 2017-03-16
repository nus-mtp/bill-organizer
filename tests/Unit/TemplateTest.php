<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Template;

class TemplateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->template = factory(Template::class)->create();
    }

    public function testGetRecordIssuer()
    {
        $this->assertNotNull($this->template->record_issuer);
    }

    public function testGetIssueDateArea()
    {
        $this->assertNotNull($this->template->issue_date_area);
    }

    public function testGetDueDateArea()
    {
        $this->assertNotNull($this->template->due_date_area);
    }

    public function testGetPeriodArea()
    {
        $this->assertNotNull($this->template->period_area);
    }

    public function testGetAmountArea()
    {
        $this->assertNotNull($this->template->amount_area);
    }
}
