<?php

/*
https://api.telegram.org/botТОКЕН_БОТА/setWebhook?url=ВАШ_URL 

https://api.telegram.org/bot1663389371:AAF02ME85qIV2VLkDHHfVDFSRgtEzcHfx-8/setWebhook?url=https://web2035pr32.azurewebsites.net 


c5a86a1a33d9fd41aa89f339bcfdf6ed
*/
    //Подключаем автозагрузчик библиотеки классов нашего проекта
require_once "vendor/autoload.php"; 

$token = "1737983734:AAGNlxC82uk4qRS146qe-UlOtYZIIM257Lc";

try {
    $bot = new \TelegramBot\Api\Client($token);
    // or initialize with botan.io tracker api key
    // $bot = new \TelegramBot\Api\Client('YOUR_BOT_API_TOKEN', 'YOUR_BOTAN_TRACKER_API_KEY');
    

    //Handle /ping command
    $bot->command('ping', function ($message) use ($bot) {
        $bot->sendMessage($message->getChat()->getId(), 'pong!');
    });


    //Handle /start command
    $bot->command('start', function ($message) use ($bot) {

        $answer = "Привет!!!\n\n/start\n\n";
        //Предлагаем Клиенту выбрать из меню варианты общения
    $answer = 
    $answer
    ."Сыграем в игру: камень-ножницы-бумага?\n" 
    ."/stone - твой камень\n"
    ."/scissors - твои ножницы\n"
    ."/paper - твоz бумага_\n\n"
    ."/weather_kharkiv - погода в Харькове\n"
    ."/exchenge - курс валют\n";

        $bot->sendMessage($message->getChat()->getId(), $answer);

    });

    //Handle /paper command
    $bot->command('paper', function ($message) use ($bot) {

        $answer = "А у меня ножницы - ты проиграл. \n /start";

        $bot->sendMessage($message->getChat()->getId(), $answer);
    });

    //Handle /stone command
    $bot->command('stone', function ($message) use ($bot) {

        $answer = "А у меня бумага - ты проиграл. \n /start";

        $bot->sendMessage($message->getChat()->getId(), $answer);
    });

    //Handle /scissors command
    $bot->command('scissors', function ($message) use ($bot) {

        $answer = "А у меня камень - ты проиграл. \n /start";

        $bot->sendMessage($message->getChat()->getId(), $answer);
    });
    
    //Handle text messages
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        $message = $update->getMessage();
        $id = $message->getChat()->getId();

        if ($message->getText() == "/weather_kharkiv")
        {
            $res = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=Kharkiv&units=metric&appid=c5a86a1a33d9fd41aa89f339bcfdf6ed");
            $res = json_decode($res);

            $temp_now = $res->main->temp;
            $humidity = $res->main->humidity;
            $pressure = $res->main->pressure;
            $wind = $res->wind->speed;
            $description = $res->weather[0]->description; 


            // Перевод значений на русский язык.
            switch($description)
            {
                case "mist":
                    $description = "Туман";
                break;
                case "clear sky":
                    $description = "Ясно";
                break;
                case "few clouds":
                    $description = "Малооблачно";
                break;
                case "broken clouds":
                    $description = "Облачность";
                break;
                case "light rain":
                    $description = "Небольшой дождь";
                break;
                case "sky is clear":
                    $description = "Ясно";
                break;
                case "moderate rain":
                    $description = "Дождливо";
                break;
                case "scattered clouds":
                    $description = "Облачно с прояснениями";
                break;
                case "heavy intensity rain":
                    $description = "Сильный дождь";
                break;
                case "snow":
                    $description = "Снег";
                break;
                case "overcast clouds":
                    $description = "Пасмурно";
                break;
            }

            // Заполнение данных в переменную $m для вывода сообщения.
            $answer = "Данные о погоде:  $description.\n".               
            "Температура: $temp_now.\n".            
            "Влажность, %: $humidity.\n".
            "Давление, мм: $pressure.\n".
            "Ветер, м/сек: $wind.\n";

            $bot->sendMessage($id, $answer);
        } 
        elseif ($message->getText() == "/exchenge")
        {
            $res = file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5");

            $res = json_decode($res,JSON_OBJECT_AS_ARRAY);

            //"ccy":"USD","base_ccy":"UAH","buy":"27.95000","sale":"28.41000"
            $answer = "";
            for($i = 0;$i < 4;$i++){
                $answer = $answer.  " валюта:  ".$res[$i]["ccy"]."\n".
                "основная валюта:  ".$res[$i]["base_ccy"]."\n".
                "покупка:  ".$res[$i]["buy"]."\n".
                "продажа:  ".$res[$i]["sale"]."\n";
            }

            $bot->sendMessage($message->getChat()->getId(), $answer);
        }

       
    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    file_put_contents("error.txt", $e->getMessage(), FILE_APPEND);
    $e->getMessage();
}