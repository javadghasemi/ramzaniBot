<?php

include('bootstrap.php');


use Models\User;

$token = "1528586103:AAGPEMwwD1rCvNdr_Qj0JrfjOfFYGWvY_VM";
$telegram = new Telegram($token);



if(checkEqualMessage($telegram, "/start")) {
    User::create(["username" => $telegram->username(), "name" => $telegram->FirstName()]);
    sendMessage($telegram, "سلام. به ربات حاضری خوش آمدید");
}

