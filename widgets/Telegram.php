<?php

namespace app\widgets\helpers;
use CURLFile;
use Exception;
use Yii;

class Telegram
{
    CONST OMADBEK = 64520993;
    CONST FAYZULLO = 673629439;
    CONST MUHAMMADSODIQ = 440310184;
    public static $token = '2068461713:AAH5LImS7JMsssz04dVYAM4C7dM8lCbv9mk';
    public $text;
    public $id;
    public $module;
    public $controller;
    public $user;
    public $get_ip = false;
    public function __construct($params = [
        'token' => '2068461713:AAH5LImS7JMsssz04dVYAM4C7dM8lCbv9mk',
        'id' => self::FAYZULLO,
        'text' => 'Salom',
        'module' => 'Plm',
        'controller' => 'Plm',
        'send_content' => false,
        'line' => __LINE__
    ])
    {
        if ( $_SERVER['SERVER_ADDR'] == "127.0.0.1" && !empty($params['id']) && !in_array($params['id'], [self::FAYZULLO])) {
            return false;
        }
        self::$token = $params['token'] ?? '2068461713:AAH5LImS7JMsssz04dVYAM4C7dM8lCbv9mk';
        $this->id = $params['id'] ?? self::FAYZULLO;
        $this->user = \Yii::$app->user->identity->username;
        $this->text = $params['text']."\n #line:".$params['line'] ?? 'Text yozilmadi';
        $this->module = $params['module'] ?? 'Plm';
        $this->controller = $params['controller'];
        if(empty($params['send_content'])){
           return $this->sendMessage();
        }
    }

    public function sendMessage() {
        if($this->get_ip){
            $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '#not_ip';
        }else{
            $ip = '';
        }
        define('TELEGRAM_TOKEN', self::$token);
        $url = $_SERVER['HTTP_HOST'];
        $ch = curl_init();
        $text = '#'.$url.' #'.$this->module.' '.'#'.$this->controller.'\n '.$this->text.'\n Ip : '.$ip.'\n User #'.$this->user;
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage',
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => array(
                    'chat_id' => $this->id,
                    'parse_mode' => 'html',
                    'text' => mb_substr($text,0,4096),
                ),
            )
        );
        return curl_exec($ch);
    }
    public function sendContent() {
        file_get_contents("https://api.telegram.org/bot".self::$token."/sendMessage?chat_id={$this->id}&text=" . urlencode($this->text).'&parse_mode=Markdown');
    }

    /**
     * @param array $ids
     * @param string $text
     * @param array $localSendIds
     * @param null $telegram_bot
     * @return \Generator
     */
    public static function sendMultiple(array $ids, string $text, array $localSendIds = [], $telegram_bot = null) {
        // 1053696039 -> Bahriddin
        $result = [];
        $host = ($_SERVER['SERVER_ADDR']??'');
        $url_server = ($_SERVER['SERVER_NAME']??'')."\n";
        $sended_text = $url_server.$text;
        $splited_text = @mb_substr($sended_text,0,4095);
        foreach ($ids as $id ) {
            if (!in_array($id, $localSendIds) && $host == "127.0.0.1") {
                continue;
            }
            $options = [
                'chat_id' => $id,
                'parse_mode' => "html",
                'text' => $splited_text,
            ];
            $telegram_bot = is_null($telegram_bot) ? '2068461713:AAH5LImS7JMsssz04dVYAM4C7dM8lCbv9mk': $telegram_bot;
            $url = "https://api.telegram.org/bot".$telegram_bot."/sendMessage";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $options);
            $res = curl_exec($ch);
            if (!empty(curl_error($ch))) {
                $result[$id] = json_encode(curl_error($ch));
            }
            $result[$id] = json_decode($res, 1);
        }
        if ($host == "127.0.0.1"){
            $_GET['telegram_respons'] = $result;
        }
        return $result;
    }

    public static function getMessageSend($text, $module = null, $controller = null, $action = null, $line = null, $model = null) {
        $subText = json_encode($text, JSON_PRETTY_PRINT);
        $user = Yii::$app->user->identity->username;
        $url = $_SERVER['HTTP_HOST'];
        $sendText = "#<b>".$url."</b> \n controller: <b>".$module."/".$controller."/".$action."</b> \n\n ".$subText."\n\n line: ".$line." \n user: #".$user;
        $options = [
            'chat_id' => 440310184,
            'parse_mode' => "html",
            'text' => mb_substr($sendText,0,4096)
        ];
        $telegram_bot = '1119831722:AAFlkTz8jzSQn4g4b-AHs-CThDKrBmP9wF0';
        $url = "https://api.telegram.org/bot".$telegram_bot."/sendMessage";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $options);
        curl_exec($ch);
    }

    public static function sendDocument($ids, $filePath, $caption = '', $local = [], $telegramToken = null)
    {
        $host = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        $caption = mb_substr($caption, 0, 1024);
        $result = [];
        $file = new CURLFile($filePath);
        $token = $telegramToken ?? self::$token;
        foreach ($ids as $id ) {
            if (!in_array($id, $local) && $host == "127.0.0.1") {
                continue;
            }
            $res = self::request([
                'chat_id' => $id,
                'caption' => $caption,
                'parse_mode' => "html",
                'document' => $file,
            ], 'sendDocument', $token);
            $result[$id] = json_decode($res, 1);
        }
        $_GET['telegram_respons'][__LINE__] = $result;
        return $result;
    }
    public static function request($options, $method, $token = null) {
        $telegram_bot = $token ?? '1119831722:AAFlkTz8jzSQn4g4b-AHs-CThDKrBmP9wF0';
        $url = "https://api.telegram.org/bot".$telegram_bot."/" . $method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $options);
        $res = curl_exec($ch);
        if (!empty(curl_error($ch))) {
            return curl_error($ch);
        }
        return $res;
    }
    public static function sendPhoto($ids, $filePath, $caption = '', $local = [], $telegramToken = null)
    {
        $host = $_SERVER['SERVER_ADDR'];
        foreach ($ids as $id ) {
            if (!in_array($id, $local) && $host == "127.0.0.1") {
                continue;
            }
            $res = self::request([
                'chat_id' => $id,
                'caption' => $caption,
                'parse_mode' => "html",
                'photo' => new \CURLFile($filePath),
            ], 'sendPhoto', $telegramToken);
            $result[$id] = json_decode($res, 1);
        }
        $_GET['telegram_response'][__LINE__] = $result;
        return $result;
    }

    public static function tgExceptionLog(array $ids, $e, $send_local_ids = []) {
        if ($e instanceof Exception == false) {
            return false;
        }
        return self::sendMultiple($ids, json_encode([
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'trace' => $e->getTrace(),
        ], JSON_PRETTY_PRINT), $ids);
    }

}