<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRewardToCausesTable extends Migration
{
    public function up()
    {
        Schema::table('causes', function (Blueprint $table) {
            if (!Schema::hasColumn('causes', 'reward')) {
                $table->string('reward')->nullable();
            }
            
        });
    }

    public function down()
    {
        Schema::table('causes', function (Blueprint $table) {
            if (Schema::hasColumn('causes', 'reward')) {
                $table->dropColumn('reward')->nullable();
            }
        });
    }
}
