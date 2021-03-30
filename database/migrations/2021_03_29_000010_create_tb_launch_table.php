<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbLaunchTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tb_launch';

    /**
     * Run the migrations.
     * @table tb_launch
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->date('operation_date');
            $table->decimal('value', 8, 2);
            $table->unsignedInteger('idtb_operation');
            $table->unsignedInteger('idtb_type_launch');
            $table->unsignedInteger('idtb_payment_type')->default('1');
            $table->unsignedInteger('idtb_caixa');
            $table->unsignedInteger('idtb_base');
            $table->unsignedInteger('status');
            $table->unsignedInteger('idtb_closing');

            $table->index(["idtb_base"], 'fk_tb_launch_tb_base1_idx');

            $table->index(["idtb_closing"], 'fk_tb_launch_tb_closing_record1_idx');

            $table->index(["idtb_caixa"], 'fk_tb_launch_tb_caixa_idx');

            $table->index(["idtb_payment_type"], 'fk_tb_launch_tb_payment_type_idx');

            $table->index(["id_user"], 'fk_tb_launch_tb_cad_user1_idx');

            $table->index(["idtb_operation"], 'fk_tb_launch_tb_operation_idx');

            $table->index(["idtb_type_launch"], 'fk_tb_launch_tb_type_launch_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('idtb_base', 'fk_tb_launch_tb_base1_idx')
                ->references('id')->on('tb_base')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_user', 'fk_tb_launch_tb_cad_user1_idx')
                ->references('id')->on('tb_cad_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_caixa', 'fk_tb_launch_tb_caixa_idx')
                ->references('id')->on('tb_caixa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_closing', 'fk_tb_launch_tb_closing_record1_idx')
                ->references('id')->on('tb_closing')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_operation', 'fk_tb_launch_tb_operation_idx')
                ->references('id')->on('tb_operation')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_payment_type', 'fk_tb_launch_tb_payment_type_idx')
                ->references('id')->on('tb_payment_type')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_type_launch', 'fk_tb_launch_tb_type_launch_idx')
                ->references('id')->on('tb_type_launch')
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
        Schema::dropIfExists($this->tableName);
    }
}
