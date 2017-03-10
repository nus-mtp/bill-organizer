<?php


namespace Tests\Unit;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;


class UserTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
        Eloquent::unguard(); //disables mass assignment protection

    }
    public function test_create_users()
    {
        $users = factory(User::class,10)->make();
        self::assertEquals(10, count($users));
    }
}
