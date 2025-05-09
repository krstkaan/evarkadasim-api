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
        Schema::create('building_ages', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Örn: 0-5 yıl, 6-10 yıl, 11-20 yıl, 20+ yıl
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_ages');
    }
};
