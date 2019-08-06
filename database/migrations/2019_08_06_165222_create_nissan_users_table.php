<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNissanUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nissan_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable()->default(null);
            $table->string('nombres')->nullable()->default(null);
            $table->string('apellidos')->nullable()->default(null);
            $table->string('telefono')->nullable()->default(null);
            $table->string('dpi')->nullable()->default(null);
            $table->date('nacimiento')->nullable()->default(null);
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
        Schema::dropIfExists('nissan_users');
    }
}
