<?php

require "../bootstrap.php";


use Illuminate\Database\Capsule\Manager as Capsule;



Capsule::schema()->create('users', function($table) {
    $table->increments('id');

    $table->string('name')->nullable();

    $table->string('username');

    $table->timestamps();
});

print('ok');