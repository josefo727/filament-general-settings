<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value');
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = FilamentGeneralSettingsServiceProvider::getTableName();
        
        Schema::dropIfExists($tableName);
    }
};