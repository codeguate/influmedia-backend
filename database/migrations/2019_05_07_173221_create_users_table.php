<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable()->default(null);
            $table->string('password');
            $table->string('email');
            $table->string('nombres')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);
            $table->string('apellidos')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->string('telefono')->nullable()->default(null);
            $table->string('dpi')->nullable()->default(null);
            $table->date('nacimiento')->nullable()->default(null);
            $table->string('foto')->nullable()->default(null);
            $table->dateTime('last_conection')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('facebook_id')->nullable()->default(null);
            $table->string('one_signal_id')->nullable()->default(null);
            $table->string('pic1')->nullable()->default(null);
            $table->string('pic2')->nullable()->default(null);
            $table->string('pic3')->nullable()->default(null);
            $table->integer('state')->nullable()->default(1);

            $table->integer('rol')->nullable()->default(null)->unsigned();
            $table->foreign('rol')->references('id')->on('roles')->onDelete('cascade');

            $table->integer('referido')->nullable()->default(null)->unsigned();
            $table->foreign('referido')->references('id')->on('users')->onDelete('cascade');
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
