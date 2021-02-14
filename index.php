<?php

include('bootstrap.php');

use Models\Lesson;
use Models\MotivationalPhrase;
use Models\Time;
use Models\User;


$token = "1528586103:AAGPEMwwD1rCvNdr_Qj0JrfjOfFYGWvY_VM";
$telegram = new Telegram($token);


$adminKeyboard = [
    [$telegram->buildKeyboardButton("تعریف درس"), $telegram->buildKeyboardButton("تعریف ادمین")],
    [$telegram->buildKeyboardButton("جملات انگیزشی")],
    [$telegram->buildKeyboardButton("گزارش گیری")]
];

$lessonsKeyboard = [];

$lessons = Lesson::all();
foreach ($lessons as $lesson) {
    array_push($lessonsKeyboard, [$lesson["name"]]);
}


$user = User::where("chat_id", "=", $telegram->ChatID())->first();

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
                "chat_id" => $telegram->ChatID(),
                "username" => $telegram->username(),
                "firstname" => $telegram->FirstName(),
                "lastname" => $telegram->LastName()
            ]
        );
        cacheStep($telegram, "client|start");

        $keyboard = $telegram->buildKeyBoard($lessonsKeyboard, true);
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
    } /**
     * Listen on get report
     */
    else if (checkEqualMessage($telegram, "گزارش گیری") && getStep($telegram)[1] == "start") {
        cacheStep($telegram, "admin|getReport");

        $keyboard = $telegram->buildKeyBoard([[$telegram->buildKeyboardButton("ماه جاری")]]);
        sendMessage($telegram, "لطفا عدد ماه درخواستی را به میلادی وارد کنید", $keyboard);

    } else if (getStep($telegram)[1] == "getReport" && checkEqualMessage($telegram, "ماه جاری")) {
        $now = date("m");

        $report = [
            ['آیدی کاربر', 'نام کاربری', 'نام', 'نام خانوادگی', 'نام درس', 'زمان شروع', 'زمان پایان'],
        ];

        foreach (Time::whereMonth('created_at', $now)->get() as $time) {
            array_push($report, [
                $time->user->chat_id,
                $time->user->username,
                $time->user->firstname,
                $time->user->lastname,
                $time->lesson->name,
                strval($time->created_at),
                strval($time->updated_at)
            ]);
        }

        $xlsx = SimpleXLSXGen::fromArray($report);
        $xlsx->saveAs(__DIR__ . "/excel/" . $now . ".xlsx");

        $zipStatus = makeZipArchive(__DIR__ . "/excel/" . $now . ".xlsx", $now . ".zip");

        if ($zipStatus) {
            $content = array("chat_id" => $telegram->ChatID(), "document" => "https://konkorlessons.ir/zip/" . $now . ".zip");
            $telegram->sendDocument($content);
        }

    } else if (getStep($telegram)[1] == "getReport" && gettype(intval($telegram->Text())) == "integer") {
        $month = $telegram->Text();

        $report = [
            ['آیدی کاربر', 'نام کاربری', 'نام', 'نام خانوادگی', 'نام درس', 'زمان شروع', 'زمان پایان'],
        ];

        foreach (Time::whereMonth('created_at', $month)->get() as $time) {
            array_push($report, [
                $time->user->chat_id,
                $time->user->username,
                $time->user->firstname,
                $time->user->lastname,
                $time->lesson->name,
                strval($time->created_at),
                strval($time->updated_at)
            ]);
        }

        $xlsx = SimpleXLSXGen::fromArray($report);
        $xlsx->saveAs(__DIR__ . "/excel/" . $month . ".xlsx");

        $zipStatus = makeZipArchive(__DIR__ . "/excel/" . $month . ".xlsx", $month . ".zip");

        if ($zipStatus) {
            $content = array("chat_id" => $telegram->ChatID(), "document" => "https://konkorlessons.ir/zip/" . $month . ".zip");
            $telegram->sendDocument($content);
        }
    } else if (checkEqualMessage($telegram, "جملات انگیزشی") && getStep($telegram)[1] == "start") {
        cacheStep($telegram, "admin|sendMP");
        sendMessage($telegram, "لطفا متن جمله را وارد کنید");
    } else if (getStep($telegram)[1] === "sendMP") {
        MotivationalPhrase::create([
            "text" => $telegram->Text()
        ]);

        sendMessage($telegram, "جمله مورد نظر با موفقیت اضافه شد");
    }
}

/**
 * Listen on client commands
 */
if (getStep($telegram)[0] == "client") {
    if (getStep($telegram)[1] == "start") {
        foreach ($lessons as $lesson) {
            if (checkEqualMessage($telegram, $lesson->name)) {
                $keyboard = $telegram->buildKeyBoard([[$telegram->buildKeyboardButton("شروع")]]);

                cacheStep($telegram, "client|choosedLesson|" . $lesson->name . "|" . $lesson->id);
                sendMessage($telegram, "درس " . $lesson->name . " - برای ثبت ساعت شروع، روی دکمه شروع کلیک کنید", $keyboard);
                return;
            }
        }
    }

    /**
     * Listen on start command
     */
    if (getStep($telegram)[1] == "choosedLesson" && checkEqualMessage($telegram, "شروع")) {
        $steps = getStep($telegram);

        $time = $user->times()->create([
            "lesson_id" => $steps[3]
        ]);

        cacheStep($telegram, $steps[0] . "|" . $steps[1] . "|" . $steps[2] . "|" . $steps[3] . "|" . "begin|" . $time->id);

        $keyboard = $telegram->buildKeyBoard([[$telegram->buildKeyboardButton("پایان")]]);

        $MPText = MotivationalPhrase::inRandomOrder()->first();

        sendMessage($telegram, $MPText->text, $keyboard);
    }

    /**
     * Listen on finish command
     */
    if (getStep($telegram)[1] == "choosedLesson" && getStep($telegram)[4] == "begin" && checkEqualMessage($telegram, "پایان")) {
        $steps = getStep($telegram);

        $time = $user->times()->where('id', $steps[5])->update([
            "finish" => 1
        ]);

        cacheStep($telegram, "client|start");

        $keyboard = $telegram->buildKeyBoard($lessonsKeyboard, true);
        sendMessage($telegram, "پایان درس با موفقیت به ثبت رسید", $keyboard);
    }
}