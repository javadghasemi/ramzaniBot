<?php

require "../bootstrap.php";


use Illuminate\Database\Capsule\Manager as Capsule;



Capsule::schema()->create('users', function($table) {
    $table->increments('id');

    $table->string('chat_id');

    $table->string('firstname')->nullable();

    $table->string('lastname')->nullable();

    $table->string('username')->nullable();

    $table->timestamps();
});

print('ok');