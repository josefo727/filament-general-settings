<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Unit;

use Josefo727\FilamentGeneralSettings\Tests\TestCase;
use PDO;

class SqliteTest extends TestCase
{
    /** @test */
    public function it_can_connect_to_database()
    {
        // Skip the test if PDO SQLite is not available
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('PDO SQLite extension is not loaded. Using alternative database driver.');
            return;
        }

        $this->assertTrue(extension_loaded('sqlite3'), 'SQLite3 extension is not loaded');
        $this->assertTrue(extension_loaded('pdo_sqlite'), 'PDO SQLite extension is not loaded');

        try {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create a simple table
            $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)');

            // Insert a row
            $pdo->exec("INSERT INTO test (name) VALUES ('test')");

            // Query the row
            $stmt = $pdo->query('SELECT * FROM test');
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->assertEquals('test', $row['name']);
            $this->assertTrue(true, 'SQLite connection and operations successful');
        } catch (\Exception $e) {
            $this->fail('SQLite connection failed: ' . $e->getMessage());
        }
    }
}
