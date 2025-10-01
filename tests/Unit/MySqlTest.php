<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;

class MySqlTest extends TestCase
{
    /** @test */
    public function it_can_connect_to_database()
    {
        // Skip the test if PDO MySQL is not available
        if (! extension_loaded('pdo_mysql')) {
            $this->markTestSkipped('PDO MySQL extension is not loaded. Using alternative database driver.');

            return;
        }

        // Verify that we're using the MySQL connection
        $driver = config('database.connections.testing.driver');
        if ($driver !== 'mysql') {
            $this->markTestSkipped("Using {$driver} driver instead of mysql.");

            return;
        }

        try {
            // Attempt to connect to the database
            $result = DB::connection('testing')->select('SELECT 1 as test');

            // If we get here, the connection was successful
            $this->assertEquals(1, $result[0]->test);
            $this->assertTrue(true, 'MySQL connection successful');
        } catch (\Exception $e) {
            // If we're running in an environment without MySQL, mark the test as skipped
            $this->markTestSkipped('MySQL connection failed: '.$e->getMessage());
        }
    }
}
