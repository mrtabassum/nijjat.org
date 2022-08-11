<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTaxToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('monthly_income')->nullable();
            $table->integer('annual_income')->nullable();
            $table->string('income_source')->nullable();
            $table->string('nid_image')->nullable();
            $table->string('driving_license_image')->nullable();
            $table->string('passport_image')->nullable();
            $table->string('tax_verify_status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('monthly_income');
            $table->dropColumn('annual_income');
            $table->dropColumn('income_source');
            $table->dropColumn('nid_image');
            $table->dropColumn('driving_license_image');
            $table->dropColumn('passport_image');
            $table->dropColumn('tax_verify_status');
        });
    }
}
