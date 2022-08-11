<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSubcribeStatusToNewslettersTable extends Migration
{

    public function up()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            if (!Schema::hasColumn('newsletters', 'subscribe_status')) {
                $table->integer('subscribe_status')->default(1);
            }
          
        });
    }

    public function down()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            if (Schema::hasColumn('newsletters', 'subscribe_status')) {
                $table->dropColumn('subscribe_status');
            }
        });
    }
}
