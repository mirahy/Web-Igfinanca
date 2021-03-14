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
            $table->integer('idtb_payment_type')->unsigned()->default('1');

            $table->index(["idtb_caixa"], 'fk_tb_launch_tb_caixa_idx');

            $table->index(["idtb_payment_type"], 'fk_tb_launch_tb_payment_type_idx');

            $table->foreign('id_user', 'fk_tb_launch_tb_cad_user1_idx')
                ->references('id')->on('tb_cad_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_caixa', 'fk_tb_launch_tb_caixa_idx')
                ->references('id')->on('tb_caixa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_operation', 'fk_tb_launch_tb_operation_idx')
                ->references('id')->on('tb_operation')
                ->onDelete('no action')
                ->onUpdate('no action');
                
            $table->foreign('idtb_closing', 'fk_tb_launch_tb_closing_record1_idx')
                ->references('id')->on('tb_closing')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_type_launch', 'fk_tb_launch_tb_type_launch_idx')
                ->references('id')->on('type_launch')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_payment_type', 'fk_tb_launch_tb_payment_type_idx')
                ->references('id')->on('tb_payment_type')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_base', 'fk_tb_launch_tb_base1_idx')
                ->references('id')->on('tb_base')
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
