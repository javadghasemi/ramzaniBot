<?php

function sendMessage($telegram, $message)
{
    $chatId = $telegram->chatID();

    $content = array("chat_id" => $chatId, "text" => $message);

    $telegram->sendMessage($content);
}


function checkEqualMessage($telegram, $message) 
{
    $sendedMessageFromUser = $telegram->Text();

    if ($sendedMessageFromUser === $message) {
        return true;
    }

    return false;
}