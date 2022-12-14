<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('support_ticket_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('notify')->nullable();
            $table->string('attachment')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('support_ticket_messages');
    }
}
