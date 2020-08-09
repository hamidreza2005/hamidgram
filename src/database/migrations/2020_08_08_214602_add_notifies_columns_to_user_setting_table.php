<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifiesColumnsToUserSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->boolean('notify_when_get_like')->default(true)->after('two_step_verification_code_expire_at');
            $table->boolean('notify_when_get_comment')->default(true)->after('notify_when_get_like');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('notify_when_get_like');
            $table->dropColumn('notify_when_get_comment');
        });
    }
}
