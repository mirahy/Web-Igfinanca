<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasLaunchTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'user_has_launch';

    /**
     * Run the migrations.
     * @table user_has_launch
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('idtb_launch');

            $table->index(["idtb_launch"], 'fk_tb_cad_user_has_tb_launch_tb_launch1_idx');

            $table->index(["user_id"], 'fk_tb_cad_user_has_tb_launch_tb_cad_user1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('user_id', 'fk_tb_cad_user_has_tb_launch_tb_cad_user1_idx')
                ->references('id')->on('tb_cad_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_launch', 'fk_tb_cad_user_has_tb_launch_tb_launch1_idx')
                ->references('id')->on('tb_launch')
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
