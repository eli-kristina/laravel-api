<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id')->nullable()->index('conversations_from_user_id_foreign');
            $table->unsignedBigInteger('to_user_id')->nullable()->index('conversations_to_user_id_foreign');
            $table->string('last_message');
            $table->timestamps();
            
            $table->foreign('from_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('to_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
