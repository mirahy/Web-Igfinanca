<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUserSocialTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tb_user_social';

    /**
     * Run the migrations.
     * @table tb_user_social
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id');
            $table->string('social_network', 45);
            $table->string('social_id', 45);
            $table->string('social_email', 100);
            $table->string('social_avatar', 45);

            $table->index(["user_id", "social_email"], 'fk_tb_user_social_tb_cad_user1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('user_id', 'fk_tb_user_social_tb_cad_user1_idx')
                ->references('id')->on('tb_cad_user')
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
