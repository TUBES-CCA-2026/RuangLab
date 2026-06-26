<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixNotificationsNotifiableIdToString extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE notifications MODIFY notifiable_id VARCHAR(36) NOT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE notifications MODIFY notifiable_id BIGINT UNSIGNED NOT NULL');
    }
}