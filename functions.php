<?php

function sendMessage($telegram, $message, $replayMarkup = null)
{
    $chatId = $telegram->chatID();

    $content = array("chat_id" => $chatId, "text" => $message, "reply_markup" => $replayMarkup);

    $telegram->sendMessage($content);
}


function checkEqualMessage($telegram, $message): bool
{
    $messageFromUser = $telegram->Text();

    if ($messageFromUser === $message) {
        return true;
    }

    return false;
}