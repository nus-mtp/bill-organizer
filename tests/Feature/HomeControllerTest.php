<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testAsGuest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testAsUser() {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/dashboard');
    }
}
