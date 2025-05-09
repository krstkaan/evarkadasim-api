<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('furniture_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Örn: Eşyalı, Eşyasız, Kısmen Eşyalı
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furniture_statuses');
    }
};
