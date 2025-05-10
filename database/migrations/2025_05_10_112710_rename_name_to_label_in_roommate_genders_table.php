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
        Schema::table('roommate_genders', function (Blueprint $table) {
            // 'name' sütununu 'label' olarak yeniden adlandır
            $table->renameColumn('name', 'label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roommate_genders', function (Blueprint $table) {
            //
        });
    }
};
