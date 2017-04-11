<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\RecordIssuer;

class DashboardControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp() {
        // gotta call parent::setUp for a correct setUp since we're overriding it.
        parent::setUp();

        $this->runDatabaseMigrations();
    }


    public function testAsGuest()
    {
        $response = $this->get('/dashboard');

        // should be redirected to login
        $response->assertRedirect('/');
    }

    public function testAsUser() {
        $user = factory(User::class)->create();
        $record_issuers = factory(RecordIssuer::class, 2)->create([
            'user_id' => $user->id
        ]);
        $record_issuer_names = $record_issuers->pluck('name')->toArray();


        $response = $this->actingAs($user)->get('/dashboard');

        // should be OK
        $response->assertStatus(200);

        // cannot use $response->assertViewHas. Somehow the expected and actual results
        // differ in the `wasRecentlyCreated` attribute
        foreach ($record_issuer_names as $name) {
            $response->assertSee(htmlentities($name, ENT_QUOTES));
        }
    }
}
