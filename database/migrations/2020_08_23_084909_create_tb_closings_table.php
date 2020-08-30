<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTbCaixa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public $set_schema_table = 'tb_closing';
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
			$table->string('month', 20);
			$table->string('year', 4);
            $table->integer('status', 2)->default('0');
            
            
            $table->timestamps();
            $table->softDeletes();

            
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
