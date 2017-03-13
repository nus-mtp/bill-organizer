<?php

namespace Tests\Support;

trait DatabaseMigrations
{
    /**
     * @before
     */
    public function runDatabaseMigrations()
    {
        // run migration in database defined in phpunit.xml (default billorg_test)
        $this->artisan('migrate');
        // seed default billorg types
        /*
        Artisan::call('migrate');
        $this->artisan('migrate');
        Artisan::call('db:seed');
        $this->artisan('db:seed');
        $this->seed('DatabaseSeeder');
        $this->session(['test' => 'session']);
        $this->seed('DatabaseSeeder');
        */
        
        $this->seed('RecordIssuerTypesSeeder');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
}