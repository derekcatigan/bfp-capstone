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
        Schema::create('trip_tracking', function (Blueprint $table) {
            $table->uuid('trip_id')->primary();

            $table->boolean('is_tracking')->default(true);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->timestamp('last_ping_at')->nullable();

            $table->timestamps();

            $table->foreign('trip_id')
                ->references('id')
                ->on('trip_tickets')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_tracking');
    }
};
