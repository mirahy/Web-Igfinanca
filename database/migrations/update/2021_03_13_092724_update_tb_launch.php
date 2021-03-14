<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTbLaunch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_launch', function (Blueprint $table) {
              

            $table->foreign('idtb_type_launch', 'fk_tb_launch_tb_type_launch_idx')
                ->references('id')->on('type_launch')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_payment_type', 'fk_tb_launch_tb_payment_type_idx')
                ->references('id')->on('tb_payment_type')
                ->onDelete('no action')
                ->onUpdate('no action');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
