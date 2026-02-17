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
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            // Basic Info
            $table->string('control_no')->nullable()->index();
            $table->date('ticket_date')->nullable();

            $table->foreignUuid('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('authorized_passenger')->nullable();
            $table->string('place')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('purpose')->nullable();

            // Time Logs
            $table->time('time_departed_garage')->nullable();
            $table->time('time_arrival_destination')->nullable();
            $table->time('time_departure_destination')->nullable();
            $table->time('time_arrival_garage')->nullable();

            // Distance & Fuel
            $table->decimal('approx_distance', 8, 2)->nullable();
            $table->decimal('balance_tank', 8, 2)->nullable();
            $table->decimal('issued_stock', 8, 2)->nullable();
            $table->decimal('purchased_trip', 8, 2)->nullable();
            $table->decimal('deduct_trip', 8, 2)->nullable();

            // Lubricants
            $table->decimal('gear_oil_issued', 8, 2)->nullable();
            $table->decimal('lub_oil_issued', 8, 2)->nullable();
            $table->decimal('grease_issued', 8, 2)->nullable();

            // Odometer
            $table->unsignedInteger('speedometer_start')->nullable();
            $table->unsignedInteger('speedometer_end')->nullable();

            $table->text('remarks')->nullable();

            // Passengers
            $table->string('passenger_name1')->nullable();
            $table->date('passenger_date1')->nullable();
            $table->string('passenger_name2')->nullable();
            $table->date('passenger_date2')->nullable();
            $table->string('passenger_name3')->nullable();
            $table->date('passenger_date3')->nullable();

            // Status
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_tickets');
    }
};
