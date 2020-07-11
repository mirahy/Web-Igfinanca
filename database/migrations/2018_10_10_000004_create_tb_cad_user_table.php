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
    public $set_schema_table = 'tb_cad_user';

    /**
     * Run the migrations.
     * @table tb_cad_user
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('idtb_profile');
            $table->unsignedInteger('idtb_base');
            $table->date('birth')->nullable()->default(null);
            $table->string('email', 100)->nullable();
            $table->string('password', 254)->nullable();
            $table->string('status', 45);
            $table->string('permission', 45);
            $table->string('token_access', 254)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["idtb_base"], 'fk_tb_cad_user_tb_base1_idx');

            $table->index(["idtb_profile"], 'fk_tb_cad_user_tb_profile1_idx');

            $table->unique(["email"], 'email_UNIQUE');


            $table->foreign('idtb_base', 'fk_tb_cad_user_tb_base1_idx')
                ->references('idtb_base')->on('tb_base')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtb_profile', 'fk_tb_cad_user_tb_profile1_idx')
                ->references('idtb_profile')->on('tb_profile')
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
