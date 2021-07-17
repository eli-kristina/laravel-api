<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->nullable()->index('conversation_details_conversation_id_foreign');
            $table->unsignedBigInteger('sender_user_id')->nullable()->index('conversation_details_sender_user_id_foreign');
            $table->string('message');
            $table->timestamps();
            
            $table->foreign('conversation_id')->references('id')->on('conversations')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('sender_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_details');
    }
}
