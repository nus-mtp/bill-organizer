<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\FieldArea;

class FieldAreaTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->field_area = factory(FieldArea::class)->create();
    }

    public function testGetPage()
    {
        $this->assertNotNull($this->field_area->page);
    }

    public function testGetX()
    {
        $this->assertNotNull($this->field_area->x);
    }

    public function testGetY()
    {
        $this->assertNotNull($this->field_area->y);
    }

    public function testGetWidth()
    {
        $this->assertNotNull($this->field_area->w);
    }

    public function testGetHeight()
    {
        $this->assertNotNull($this->field_area->h);
    }

}
