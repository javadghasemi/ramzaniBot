<?php

require "../bootstrap.php";


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;


Capsule::schema()->create('motivational_phrases', function (Blueprint $table) {
    $table->increments('id');

    $table->string("text");

    $table->timestamps();
});

print('ok');