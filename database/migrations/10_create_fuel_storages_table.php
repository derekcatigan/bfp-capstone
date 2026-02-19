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
        Schema::create('fuel_storages', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // When transaction happened
            $table->timestamp('transaction_datetime');

            // Drum / Tank / Container
            $table->string('container_type');

            // add | remove
            $table->string('transaction_type');

            // liters moved
            $table->decimal('amount', 10, 2);

            // resulting stock after transaction
            $table->decimal('running_balance', 10, 2);

            // optional reason
            $table->text('note')->nullable();

            $table->timestamps();

            // useful indexes for reports
            $table->index('transaction_datetime');
            $table->index('transaction_type');
            $table->index('container_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_storages');
    }
};
