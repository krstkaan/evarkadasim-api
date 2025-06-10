<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('match_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->unsignedBigInteger('listing_id'); // hangi ev arkadaşlığına ait
            $table->unsignedBigInteger('roommate_request_id')->nullable(); // istersen
            $table->tinyInteger('communication_score'); // 1-5
            $table->tinyInteger('sharing_score');       // 1-5
            $table->tinyInteger('overall_score');       // 1-10
            $table->boolean('would_live_again');        // true / false
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
