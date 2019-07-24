<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->nullable()->default(null);
            $table->string('vencimiento')->nullable()->default(null);
            $table->boolean('activa')->nullable()->default(false);
            $table->integer('state')->nullable()->default(1);

            $table->integer('asignado')->nullable()->default(null)->unsigned();
            $table->foreign('asignado')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigos');
    }
}
