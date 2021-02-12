<?php

function config($section, $key) {
    $config = [
        "database" => [
            "driver" => env("DRIVER", "mysql"),
            "host" => env("HOST", "localhost"),
            "database" => env("DATABASE", "ramzani_bot"),
            "username" => env("USERNAME", "root2"),
            "password" => env("PASSWORD", "1234")
        ],

    ];

    return $config[$section][$key];
}