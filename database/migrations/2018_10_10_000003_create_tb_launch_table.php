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
            $table->increments('idtb_launch');
            $table->string('operation', 45);
            $table->string('type_launch', 45);
            $table->string('origin', 100);
            $table->date('opreration_date');
            $table->string('reference_month', 20);
            $table->string('reference_year', 4);
            $table->decimal('value');
            $table->integer('idtb_base');
            $table->integer('idtb_closing')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->index(["idtb_base"], 'fk_tb_launch_tb_base1_idx');

            $table->index(["idtb_closing"], 'fk_tb_launch_tb_closing_record1_idx');


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
