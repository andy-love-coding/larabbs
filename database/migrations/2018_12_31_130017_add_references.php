<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferences extends Migration
{
    // 添加外键约束: 即主键数据被删除时，对应的外键数据也被删除 
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {

            // 当 user_id 对应的 users 的「用户」被删除时，删除该用户下的话题
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('replies', function (Blueprint $table) {

            // 当 user_id 对应的 users 表的「用户」被删除时，，删除该用户下的回复
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 当 topic_id 对应的 topics 表的「话题」被删除时，删除该话题下的回复
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    // 移除外键约束
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            // 移除外键约束
            $table->dropForeign(['user_id']);
        });

        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['topic_id']);
        });

    }
}