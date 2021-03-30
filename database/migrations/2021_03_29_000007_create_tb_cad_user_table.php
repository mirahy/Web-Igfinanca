<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbCadUserTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tb_cad_user';

    /**
     * Run the migrations.
     * @table tb_cad_user
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('idtb_profile');
            $table->unsignedInteger('idtb_base')->nullable()->default(null);
            $table->date('birth')->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('password', 254)->nullable()->default(null);
            $table->string('status', 45);
            $table->string('permission', 45)->nullable()->default(null);
            $table->string('token_access', 254)->nullable()->default(null);
            $table->rememberToken();

            $table->unique(["email"], 'email_UNIQUE');

            $table->index(["idtb_base"], 'fk_tb_cad_user_tb_base1_idx');

            $table->index(["idtb_profile"], 'fk_tb_cad_user_tb_profile1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('idtb_base', 'fk_tb_cad_user_tb_base1_idx')
                ->references('id')->on('tb_base')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_profile', 'fk_tb_cad_user_tb_profile1_idx')
                ->references('id')->on('tb_profile')
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
