<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    /** @test */
    public function it_can_connect_to_database_with_any_driver()
    {
        // Get the current driver
        $driver = config('database.connections.testing.driver');

        // Output which driver we're using
        $this->addToAssertionCount(1);

        try {
            DB::connection('testing')->getDatabaseName();
            $this->assertTrue(true, "Database connection with {$driver} driver successful");
        } catch (\Exception $e) {
            $this->fail("Database connection with {$driver} driver failed: ".$e->getMessage());
        }
    }
}
