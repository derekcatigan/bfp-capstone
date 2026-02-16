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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('plate_number')->unique();

            $table->string('vehicle_type');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('color')->nullable();

            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();

            $table->string('fuel_type')->nullable();
            $table->decimal('fuel_tank_capacity', 8, 2)->nullable();
            $table->decimal('current_fuel_level', 8, 2)->nullable();

            $table->string('status')->default('Available');

            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
