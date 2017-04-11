<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\RecordIssuerType;
use App\RecordIssuer;

class RecordIssuerControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $user, $record_issuer;

    protected function setUp() {
        // gotta call parent::setUp for a correct setUp since we're overriding it.
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->user = factory(User::class)->create();
        $this->record_issuer = factory(RecordIssuer::class)->create([
            'user_id' => $this->user->id
        ]);
    }

    // TODO: Also check that the item is retrieved/added/modified/deleted from DB accordingly


    /**
     * Tests for RecordIssuerController@show
     */
    public function testShowAsGuest()
    {
        $response = $this->get('/dashboard/record_issuers/'
            . $this->record_issuer->id);

        // should be redirected to login
        $response->assertRedirect('/');
    }

    public function testShowAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->get('dashboard/record_issuers/' . $this->record_issuer->id);

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testShowAsOwner()
    {
        $response = $this->actingAs($this->user)
            ->get('dashboard/record_issuers/' . $this->record_issuer->id);

        // should be OK
        $response->assertStatus(200);
        // Commenting this because this assertion causes problem when it contains a unicode character
        // It's escaped when rendered in HTML but in the string representation it's not
         $response->assertSee(htmlentities($this->record_issuer->name, ENT_QUOTES));
    }



    /**
     * Tests for RecordIssuerController@store
     */
    public function testStoreAsGuest()
    {
        $record_issuer_data = factory(RecordIssuer::class)->make()->toArray();
        unset($record_issuer_data['user_id']);

        $response = $this->post('/dashboard/record_issuers', $record_issuer_data);

        // should be redirected to login
        $response->assertRedirect('/');
    }

    public function testStoreWithOtherUserId()
    {
        $another_user = factory(User::class)->create();
        $record_issuer_data = factory(RecordIssuer::class)->make([
            'user_id' => $another_user->id
        ])->toArray();
        $response = $this->actingAs($this->user)
            ->post('/dashboard/record_issuers', $record_issuer_data);

        // record issuer should still be added, but it's under another_user
        $saved_record_issuer = RecordIssuer::where([
            'user_id' => $another_user->id,
            'name' => $record_issuer_data['name']
        ])->get();
        $this->assertNotNull($saved_record_issuer);
    }

    public function testNormalStore()
    {
        $record_issuer_data = factory(RecordIssuer::class)->make()->toArray();
        unset($record_issuer_data['user_id']);

        $response = $this->actingAs($this->user)
            ->post('/dashboard/record_issuers', $record_issuer_data);

        // record issuer should be saved inside the DB
        $saved_record_issuer = RecordIssuer::where([
            'user_id' => $this->user->id,
            'name' => $record_issuer_data['name']
        ])->get();
        $this->assertNotNull($saved_record_issuer);

        // should be success and redirected (back to where the user came from)
        $response->assertStatus(302);
    }



    /**
     * Tests for RecordIssuerController@destroy
     */
    public function testDestroyAsGuest()
    {
        $response = $this->delete('/dashboard/record_issuers/' . $this->record_issuer->id);

        // should be redirected to login (not Unauthorized because he's not even authenticated)
        $response->assertRedirect('/');
    }

    public function testDestroyAsAnotherUser()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->delete('/dashboard/record_issuers/' . $this->record_issuer->id);


        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testDestroyAsOwner()
    {
        $user_records = factory(Record::class)->create([
            'user_id' => $this->user->id,
            'record_issuer_id' => $this->record_issuer->id
        ]);
        $response = $this->actingAs($this->user)
            ->delete('/dashboard/record_issuers/' . $this->record_issuer->id);

        // should be deleted
        $record_issuer = RecordIssuer::find($this->record_issuer->id);
        $this->assertNull($record_issuer);

        // success and redirected back
        $response->assertStatus(302);
    }



    /**
     * Tests for RecordIssuerController@store_record
     */
    // TODO: New tests for new store methods are needed, commenting old ones
    /*
    public function testStoreRecordAsGuest()
    {
        // Prepare the data
        $user_record_data = factory(Record::class)->make([
            'record_issuer_id' => $this->record_issuer->id
        ])->toArray();
        unset($user_record_data['user_id']);

        // Send the POST request
        $response = $this->post(route('records', $this->record_issuer->id), $user_record_data);

        // should be redirected to login (not Unauthorized because he's not even authenticated)
        $response->assertRedirect('/login');
    }

    public function testStoreRecordToUnownedRecordIssuer()
    {
        // Prepare the data
        $another_user = factory(User::class)->create();
        $user_record_data = factory(Record::class)->make([
            'record_issuer_id' => $this->record_issuer->id
        ])->toArray();

        // Send the POST request
        $response = $this->actingAs($another_user)
            ->post(route('records', $this->record_issuer->id), $user_record_data);

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testStoreRecordToOwnedRecordIssuer()
    {
         Storage::fake('local');

        // Prepare the data
        $user_record_data = factory(Record::class)->make([
            'record_issuer_id' => $this->record_issuer->id
        ]);
        $record_issuer_type = DB::table('record_issuer_types')->find($this->record_issuer->type);
        $is_bill = $record_issuer_type->type === RecordIssuerType::BILLORG_TYPE_NAME;
        $due_date = $is_bill ? (clone $user_record_data->issue_date)->addDays(random_int(0, 120))->toDateString() : null;
        $user_record_data = array_merge($user_record_data->toArray(), [
            'issue_date' => $user_record_data->issue_date->toDateString(),
            'due_date' => $due_date,
            'record' => UploadedFile::fake()->create('file.pdf')
        ]);

        // Send a POST request
        $response = $this->actingAs($this->user)
            ->post(route('records', $this->record_issuer->id), $user_record_data);


        // Verify that:
        //     1. Record should be added under another_user
        $saved_record = Record::where([
            'record_issuer_id' => $this->record_issuer->id,
            'issue_date' => $user_record_data['issue_date']
        ])->first();
        $this->assertNotNull($saved_record);

         //    2. File should be saved in the storage (This didn't work. Let's wait for explanation from the Laravel developers)
         $saved_file_name = "{$saved_record->id}.pdf";
         $path_to_store =  "record_issuers/{$this->record_issuer->id}/records";
         Storage::disk('local')->assertExists($path_to_store . '/' . $saved_file_name);

        // success and redirected back
        $response->assertStatus(302);
    }
    */

}
