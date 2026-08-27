<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdMtzToTbPaymentTypeTable extends Migration
{
    public function up()
    {
        Schema::table('tb_payment_type', function (Blueprint $table) {
            $table->unsignedInteger('id_mtz')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('tb_payment_type', function (Blueprint $table) {
            $table->dropColumn('id_mtz');
        });
    }
}
