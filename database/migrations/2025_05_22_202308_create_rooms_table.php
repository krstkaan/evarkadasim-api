<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_1_id');
            $table->unsignedBigInteger('user_2_id');
            $table->unsignedBigInteger('listing_id')->nullable();
            $table->timestamps();

            $table->unique(['user_1_id', 'user_2_id', 'listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
