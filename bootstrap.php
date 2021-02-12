<?php

include (__DIR__ . '/vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;


$capsule->addConnection([
    "driver" => config("database", "driver"),

    "host" => config("database", "host"),

    "database" => config("database", "database"),

    "username" => config("database", "username"),

    "password" => config("database", "password")
]);


$capsule->setAsGlobal();

$capsule->bootEloquent();