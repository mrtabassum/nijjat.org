<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAttachmentToCauseLogsTable extends Migration
{

    public function up()
    {
        Schema::table('cause_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('cause_logs', 'manual_payment_attachment')) {
                $table->string('manual_payment_attachment')->nullable();
            }
            
        });
    }

    public function down()
    {
        Schema::table('cause_logs', function (Blueprint $table) {
            if (Schema::hasColumn('cause_logs', 'manual_payment_attachment')) {
                $table->dropColumn('manual_payment_attachment');
            }
        });
    }
}
