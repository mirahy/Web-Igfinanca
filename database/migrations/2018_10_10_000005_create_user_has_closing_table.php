<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasClosingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_has_closing';

    /**
     * Run the migrations.
     * @table user_has_closing
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('idtb_closing');

            $table->timestamps();
            $table->softDeletes();

            $table->index(["user_id"], 'fk_tb_cad_user_has_tb_closing_record_tb_cad_user1_idx');

            $table->index(["idtb_closing"], 'fk_tb_cad_user_has_tb_closing_record_tb_closing_record1_idx');


            $table->foreign('user_id', 'fk_tb_cad_user_has_tb_closing_record_tb_cad_user1_idx')
                ->references('id')->on('tb_cad_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_closing', 'fk_tb_cad_user_has_tb_closing_record_tb_closing_record1_idx')
                ->references('idtb_closing')->on('tb_closing')
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
