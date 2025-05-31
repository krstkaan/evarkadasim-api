<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rater_user_id');     // puan veren
            $table->unsignedBigInteger('target_user_id');    // değerlendirilen
            $table->unsignedTinyInteger('rating');           // 0–100 arası puan
            $table->text('comment')->nullable();             // yorum opsiyonel
            $table->timestamps();

            $table->foreign('rater_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ratings');
    }
};
