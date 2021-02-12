<?php

require "../bootstrap.php";


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;


Capsule::schema()->create('times', function (Blueprint $table) {
    $table->increments('id');

    $table->unsignedInteger('user_id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    $table->unsignedInteger('lesson_id');
    $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');

    $table->timestamps();
});

print('ok');