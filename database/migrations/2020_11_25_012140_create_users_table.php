<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integerIncrements('Id');
            $table->string('UserName',24)->notNull();
            $table->string('PassWord')->notNull();
            $table->enum('Sex',[1,2,3])->default('1');
            $table->string('Phone',11)->notNull();
            $table->string('Email',50);
            $table->tinyInteger('role_id');
            $table->timestamps();   //created和updated时间
            $table->rememberToken();    //登陆状态，存储token
            $table->enum('status',[1,2])->default('2');
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
