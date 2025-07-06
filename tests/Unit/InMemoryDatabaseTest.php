<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Unit;

use Josefo727\FilamentGeneralSettings\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InMemoryDatabaseTest extends TestCase
{
    /** @test */
    public function it_can_use_in_memory_database_for_testing()
    {
        try {
            // Create a simple table using the Schema builder
            Schema::connection('testing')->create('test_table', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
            
            // Insert a record
            DB::connection('testing')->table('test_table')->insert([
                'name' => 'Test Record'
            ]);
            
            // Retrieve the record
            $record = DB::connection('testing')->table('test_table')->first();
            
            // Verify the record was inserted correctly
            $this->assertEquals('Test Record', $record->name);
            $this->assertTrue(true, 'In-memory database works for testing');
        } catch (\Exception $e) {
            $this->fail('In-memory database failed: ' . $e->getMessage());
        }
    }
}
