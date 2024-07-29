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
        Schema::create('twod_settings', function (Blueprint $table) {
            $table->id();
            $table->date('result_date'); // Date of the game result
            $table->time('result_time'); // Time of the game result
            $table->string('result_number', 2)->nullable(); // Nullable result number (two digits)
            $table->enum('session', ['morning', 'evening']);
            $table->enum('status', ['open', 'closed'])->default('closed'); // New status column
            $table->time('closed_time')->nullable();
            $table->enum('prize_status', ['open', 'closed'])->default('closed'); // New status column
            // $table->enum('admin_log', ['open', 'closed'])->default('closed'); // New status column
            // $table->enum('user_log', ['open', 'closed'])->default('closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twod_settings');
    }
};
