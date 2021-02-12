<?php

include('bootstrap.php');

use Models\Lesson;
use Models\User;


$token = "1528586103:AAGPEMwwD1rCvNdr_Qj0JrfjOfFYGWvY_VM";
$telegram = new Telegram($token);


$adminKeyboard = [
    [$telegram->buildKeyboardButton("تعریف درس"), $telegram->buildKeyboardButton("تعریف ادمین")],
    [$telegram->buildKeyboardButton("جملات انگیزشی")],
    [$telegram->buildKeyboardButton("گزارش گیری")]
];

$lessonsKeyboard = [];

foreach (Lesson::all() as $lesson) {
    array_push($lessonsKeyboard, [$lesson["name"]]);
}


$user = User::where("user_id", "=", $telegram->ChatID())->first();

/**
 * Check message for equal to "/start"
 */
if (checkEqualMessage($telegram, "/start")) {
    if ($user) {
        if (isSuperuser($user)) {
            cacheStep($telegram, "admin|start");

            $keyboard = $telegram->buildKeyBoard($adminKeyboard, true);
            sendMessage($telegram, "سلام. خوش آمدید. لطفا گزینه مورد نظر را انتخاب کنید", $keyboard);
        } else {
            cacheStep($telegram, "client|start");

            $keyboard = $telegram->buildKeyBoard($lessonsKeyboard, true);
            sendMessage($telegram, "سلام. خوش آمدید. لطفا گزینه مورد نظر را انتخاب کنید", $keyboard);
        }
    } else {
        $user = User::create(
            [
                "user_id" => $telegram->ChatID(),
                "username" => $telegram->username(),
                "firstname" => $telegram->FirstName(),
                "lastname" => $telegram->LastName()
            ]
        );
        cacheStep($telegram, "client|start");

        $keyboard = $telegram->buildKeyBoard($lessonsKeyboard);
        sendMessage($telegram, "تبریک میگم. شما در ربات ثبت شدید. لطفا درس مورد نظر را انتخاب کنید", $keyboard);
    }
}

/**
 * Check message for equal to "تعریف درس"
 */

if (getStep($telegram)[0] == "admin") {
    if (checkEqualMessage($telegram, "تعریف درس") && getStep($telegram)[1] == "start") {

        cacheStep($telegram, "admin|createLesson");
        sendMessage($telegram, "لطفا نام درس مورد نظر را وارد نمایید");

    } else if (getStep($telegram)[1] == "createLesson") {
        $lessonName = $telegram->getData()['message']['text'];
        if (!Lesson::where("name", "=", $lessonName)->first()) {
            Lesson::create(["name" => $lessonName]);

            sendMessage($telegram, "درس مورد نظر با موفقیت ثبت شد");
        } else {
            sendMessage($telegram, "درس مورد نظر از قبل وجود دارد");
        }
    }
}

if (getStep($telegram)[0] == "client") {

}

