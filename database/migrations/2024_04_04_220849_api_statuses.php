<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_statuses', function (Blueprint $table) {
            $table->id();
            $table->dateTime ('dateImport')->nullable()->date;
            $table->string ('status')->nullable();
            $table->string ('memoryConsumed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_statuses');
    }
};