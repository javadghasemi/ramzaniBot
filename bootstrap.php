<?php

include (__DIR__ . '/vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;


$capsule->addConnection([
    "driver" => "mysql",

    "host" => "localhost",

    "database" => "ramzani_bot",

    "username" => "admin",

    "password" => "1234"
]);


$capsule->setAsGlobal();

$capsule->bootEloquent();