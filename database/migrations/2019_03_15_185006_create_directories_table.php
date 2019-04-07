<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('root_dir_id')->unsigned();
            $table->integer('sub_dir_id',false,false);
            $table->string('sub_dir');
            $table->string('icon');
            $table->text('dir_path');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('root_dir_id')->references('id')
                ->on('file_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directories');
    }
}
