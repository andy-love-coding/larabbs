<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();              // 86c99b1f-ad35-4b77-b6eb-861cd251a142
            $table->string('type');                     // App\Notifications\TopicReplied
            $table->morphs('notifiable');               // notifiable_id = 1 ; notifiable_type = App\Models\User
            $table->text('data');                       // 具体的通知内容，json格式，在通知类中定义
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
