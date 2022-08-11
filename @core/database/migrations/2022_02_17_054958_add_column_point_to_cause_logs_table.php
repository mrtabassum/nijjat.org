<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPointToCauseLogsTable extends Migration
{
    public function up()
    {
        Schema::table('cause_logs', function (Blueprint $table) {

            if (!Schema::hasColumn('cause_logs', 'reward_point')) {
                $table->integer('reward_point')->nullable();
            }
            if (!Schema::hasColumn('cause_logs', 'reward_amount')) {
                $table->integer('reward_amount')->nullable();
            }
        
        });
    }

    public function down()
    {
        Schema::table('cause_logs', function (Blueprint $table) {
            if (Schema::hasColumn('cause_logs', 'reward_amount')) {
                $table->dropColumn('reward_amount');
            }
            if (Schema::hasColumn('cause_logs', 'point')) {
                $table->dropColumn('point');
            }
        });
    }
}
