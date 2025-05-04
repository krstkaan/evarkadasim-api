<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIlilcemahalleTable extends Migration
{
    public function up(): void
    {
        Schema::create('ililcemahalle', function (Blueprint $table) {
            $table->id();
            $table->string('SehirIlceMahalleAdi');
            $table->unsignedBigInteger('UstID')->default(0);
            $table->decimal('minlongitude', 10, 6)->nullable();
            $table->decimal('minlatitude', 10, 6)->nullable();
            $table->decimal('maxlongitude', 10, 6)->nullable();
            $table->decimal('maxlatitude', 10, 6)->nullable();
            $table->unsignedBigInteger('MahalleID')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ililcemahalle');
    }
}
