<?php
/**
 * Created by PhpStorm.
 * User: Sher
 * Date: 17.02.2019
 * Time: 13:04
 */

namespace app\controllers;

use aki\telegram\base\Command;
use aki\telegram\Telegram;
use Yii;
use yii\rest\Controller;

class  TelegramController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->language = 'uz';
        Command::run("/start", function($telegram){
            $result = $telegram->sendMessage([
                'chat_id' => $telegram->input->message->chat->id,
                "text" => "hello"
            ]);
        });
        Command::run("salom", function(Telegram $telegram){
            $result = $telegram->send('message',[
                'chat_id' => $telegram->input->message->chat->id,
                "text" => "Salom-salom"
            ]);
        });
    }
}

