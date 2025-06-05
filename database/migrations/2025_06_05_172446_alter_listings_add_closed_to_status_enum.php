<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AlterListingsAddClosedToStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `listings` MODIFY `status` ENUM('pending','approved','rejected','closed') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `listings` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
}

