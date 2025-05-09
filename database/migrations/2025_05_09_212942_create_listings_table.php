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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('description');
            $table->decimal('rent_price', 10, 2);
            $table->integer('square_meters');

            $table->foreignId('roommate_gender_id')->constrained();
            $table->foreignId('age_range_id')->constrained();
            $table->foreignId('house_type_id')->constrained();
            $table->foreignId('furniture_status_id')->constrained();
            $table->foreignId('heating_type_id')->constrained();
            $table->foreignId('building_age_id')->constrained();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
