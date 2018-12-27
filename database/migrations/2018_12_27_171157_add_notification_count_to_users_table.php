<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationCountToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // table() 方法增加字段
        Schema::table('users', function (Blueprint $table) {
            $table->integer('notification_count')->unsigned()->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // table() 方法删除字段
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_count');
        });
    }
}
