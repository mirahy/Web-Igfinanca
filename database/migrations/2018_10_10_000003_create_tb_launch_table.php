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
    public $set_schema_table = 'tb_launch';

    /**
     * Run the migrations.
     * @table tb_launch
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->date('operation_date');
            $table->string('reference_month', 20);
            $table->string('reference_year', 4);
            $table->decimal('value');
            $table->integer('idtb_caixa')->unsigned();
            $table->integer('idtb_operation')->unsigned();
            $table->integer('idtb_type_launch')->unsigned();
            $table->integer('idtb_base')->unsigned();
            $table->integer('status')->unsigned();
            $table->integer('idtb_closing')->unsigned();
            

            $table->timestamps();
            $table->softDeletes();

            $table->index(["id_user"], 'fk_tb_launch_tb_cad_user1_idx');

            $table->index(["idtb_caixa"], 'fk_tb_launch_tb_caixa_idx');

            $table->index(["idtb_operation"], 'fk_tb_launch_tb_operation_idx');

            $table->index(["idtb_base"], 'fk_tb_launch_tb_base1_idx');

            $table->index(["idtb_closing"], 'fk_tb_launch_tb_closing_record1_idx');


            $table->foreign('id_user', 'fk_tb_launch_tb_cad_user1_idx')
                ->references('id_user')->on('tb_cad_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_caixa', 'fk_tb_launch_tb_caixa_idx')
                ->references('idtb_caixa')->on('tb_caixa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_operation', 'fk_tb_launch_tb_operation_idx')
                ->references('idtb_operation')->on('tb_operation')
                ->onDelete('no action')
                ->onUpdate('no action');
                
            $table->foreign('idtb_closing', 'fk_tb_launch_tb_closing_record1_idx')
                ->references('idtb_closing')->on('tb_closing')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_base', 'fk_tb_launch_tb_base1_idx')
                ->references('idtb_base')->on('tb_base')
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
       Schema::dropIfExists($this->set_schema_table);
     }
}
