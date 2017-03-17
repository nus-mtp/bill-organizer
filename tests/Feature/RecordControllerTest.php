<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Tests\Support\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\Record;

class RecordControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp() {
        // gotta call parent::setUp for a correct setUp since we're overriding it.
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->user = factory(User::class)->create();
        $this->record_issuer = factory(RecordIssuer::class)->create([
            'user_id' => $this->user->id
        ]);
        // TODO: Fix this. This was repeated in UserRecordControllerTest. Due date must be updated according
        // TODO: to the nature of the record_issuer
        $record_issuer_type = DB::table('record_issuer_types')->find($this->record_issuer->type);
        $is_bill = $record_issuer_type->type === RecordIssuerType::BILLORG_TYPE_NAME;
        $due_date = $is_bill ? Carbon::now()->addDays(random_int(0, 90))->toDateString() : null;
        $this->record = factory(Record::class)->create([
            'user_id' => $this->user->id,
            'due_date' => $due_date,
            'record_issuer_id' => $this->record_issuer->id
        ]);
    }

    /**
     * Tests for RecordController@show
     */
    public function testShowAsGuest() {
        $response = $this->get(route('show_record_file', $this->record->id));

        // Unauthenticated users should be redirected to login
        $response->assertRedirect('/login');
    }

    public function testShowAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->get(route('show_record_file', $this->record->id));

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testShowAsOwner()
    {
        Response::spy();

        $response = $this->actingAs($this->user)
            ->get(route('show_record_file', $this->record->id));

        $response->assertStatus(200);
    }

    /**
     * Tests for RecordController@download
     */
    public function testDownloadAsGuest() {
        $response = $this->get(route('download_record_file', $this->record->id));

        // Unauthenticated users should be redirected to login
        $response->assertRedirect('/login');
    }

    public function testDownloadAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->get(route('download_record_file', $this->record->id));

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testDownloadAsOwner()
    {
        Response::spy();

        $response = $this->actingAs($this->user)
            ->get(route('show_record_file', $this->record->id));

        $response->assertStatus(200);
    }

    /**
     * Tests for RecordController@destroy
     */
    public function testDestroyAsGuest() {
        $response = $this->delete(route('delete_record_file', $this->record->id));

        // Unauthenticated users should be redirected to login
        $response->assertRedirect('/login');
    }

    public function testDestroyAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->delete(route('delete_record_file', $this->record->id));

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testDestroyAsOwner()
    {
        // Not sure how to test this one. Storage::spy didn't mock the Storage
    }

    /**
     * Tests for RecordController@edit
     */
    public function testEditAsGuest() {
        $response = $this->get(route('edit_record', $this->record->id));

        // Unauthenticated users should be redirected to login
        $response->assertRedirect('/login');
    }

    public function testEditAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $response = $this->actingAs($another_user)
            ->get(route('edit_record', $this->record->id));

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testEditAsOwner()
    {
        $response = $this->actingAs($this->user)
            ->get(route('edit_record', $this->record->id));

        $response->assertStatus(200);
    }

    /**
     * Tests for RecordController@edit
     */
    public function testUpdateAsGuest() {
        $update_record_data = factory(Record::class)->make()->toArray();
        $response = $this->put(route('update_record', $this->record->id), $update_record_data);

        // Unauthenticated users should be redirected to login
        $response->assertRedirect('/login');
    }

    public function testUpdateAsNonOwner()
    {
        $another_user = factory(User::class)->create();
        $update_record_data = ['amount' => 999];


        $response = $this->actingAs($another_user)
            ->put(route('update_record', $this->record->id), $update_record_data);

        // should be Unauthorized
        $response->assertStatus(403);
    }

    public function testUpdateAsOwner()
    {
        Storage::fake('local');

        $update_record_data = [
            'amount' => 999,
            'issue_date' => $this->record->issue_date->format('d/m/Y'),
            'record_file' => UploadedFile::fake()->create('new_file.pdf')
        ];
        $response = $this->actingAs($this->user)
            ->put(route('update_record', $this->record->id), $update_record_data);

        // Assert that new file should be saved
        $saved_file_name = $this->record_issuer->name . '_' . $this->record->issue_date->toDateString() . '.pdf';
        $path_to_store =  "/users/{$this->user->id}/record_issuers/{$this->record_issuer->id}/records/";
        Storage::disk('local')->assertExists($path_to_store . '/' . $saved_file_name);

        // Assert successful and redirected back
        $response->assertStatus(302);
    }

}
