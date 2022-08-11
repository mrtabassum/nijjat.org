<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{

    public function up()
    {
        if (Schema::hasTable('rewards')) {
            return;
        }
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('reward_title')->nullable();
            $table->bigInteger('reward_goal_from');
            $table->bigInteger('reward_goal_to');
            $table->string('reward_point');
            $table->bigInteger('reward_amount')->nullable();
            $table->string('reward_expire_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}
