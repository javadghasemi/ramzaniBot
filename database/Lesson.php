<?php

require "../bootstrap.php";


use Illuminate\Database\Capsule\Manager as Capsule;



Capsule::schema()->create('lessons', function($table) {
    $table->increments('id');

    $table->string('name');
});

print('ok');