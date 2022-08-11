<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowCampaignsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('user_follow')) {
            return;
        }
        Schema::create('user_follow', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('campaign_owner_id')->unsigned();
            $table->string('follow_status')->default('unfollow');
            $table->string('user_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
