<?php
/**
 * Created by PhpStorm.
 * User: Sher
 * Date: 17.02.2019
 * Time: 13:04
 */

namespace app\controllers;

use app\models\TelegramHistory;
use app\models\TelegramHistoryItems;
use app\models\Users;
use app\modules\hr\models\HrEmployeeRelPosition;
use app\modules\hr\models\UsersRelationHrDepartments;
use app\modules\plm\models\BaseModel;
use app\modules\plm\models\PlmDocItemEquipments;
use app\modules\plm\models\PlmDocItemProducts;
use app\modules\plm\models\PlmNotificationRelDefect;
use app\modules\plm\models\PlmNotificationsList;
use app\modules\plm\models\PlmNotificationsListRelReason;
use app\modules\plm\models\PlmSectorRelHrDepartment;
use app\modules\references\models\Reasons;
use app\widgets\Telegram;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Yii;
use neokyuubi\PhantomJs\Client;
use yii\web\Controller;

class  TelegramController extends Controller
{
    public $bot_api_key  = '5268528798:AAErwKMllti1zmkTRQ34FUros2PSOwF4TCo';
    public $bot_username = 'test_uchun_uzbekistan_bot';

    public function actionTest()
    {
        $client = Client::getInstance();
        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $width  = 800;
        $height = 600;
        $top    = 0;
        $left   = 0;

        $request = $client->getMessageFactory()->createCaptureRequest('http://plm.loc/uz/plm/plm-notifications-list/view-bot?id=49&user_id=1', 'GET');
        $request->setOutputFile('uploads/file.jpg');
        $request->setViewportSize($width, $height);
        $request->setCaptureDimensions($width, $height, $top, $left);

        $response = $client->getMessageFactory()->createResponse();

// Send the request
        $client->send($request, $response);
        \yii\helpers\VarDumper::dump($response,10,true);
    }
    public function actionIndex()
    {
        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($this->bot_api_key, $this->bot_username);

            // Set webhook
            $request = file_get_contents('php://input');
            $request = json_decode($request,true);
            $chat_id = null;
            if(isset($request['message'])) {
                $chat_id = $request['message']['chat']['id'];
            }
            if(isset($request['callback_query'])){
                $chat_id = $request['callback_query']['message']['chat']['id'];
            }
            if(isset($request['poll_answer'])){
                $chat_id = $request['poll_answer']['user']['id'];
            }
            if(isset($request['inline_query'])){
                $chat_id = $request['inline_query']['from']['id'];
            }
//            $user = Users::findOne(['telegram_id' => $chat_id, 'status_id' => 1]);
            $user = Users::findOne(1);
            if($user){
                $update_id = $request['update_id'];
                if(isset($request['message'])) {
                    $text = $request['message']['text'];
                    $message_id = $request['message']['message_id'];
                    $check_history = TelegramHistory::findOne(['chat_id' => $chat_id]);
                    switch ($text){
                        case '/start':
                            $employee = $user->hrEmployees[0]->hrEmployee;
                            if ($check_history){
                                $check_history->delete();
                            }
                            Request::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => 'Assalom alaykum, '.$employee->firstname.' '.$employee->lastname."! /tekshirish ni bosib tasdiqlanadigan hujjatlarni ko'rishingiz mumkin.",
                                'reply_markup' => Keyboard::remove(['selective' => true])
                            ]);
                            break;
                        case "⏪ Dokumentlarga qaytish":
                        case '/tekshirish':
                            if ($check_history){
                                $check_history->delete();
                            }
                            $this->sendWork(['Request' => new Request(),'chat_id'=> $request['message']['chat']['id'], 'user_id' => $user->id]);
                            break;
                        case "❌ ".Yii::t('app', 'Rad qilish'):
                            Request::deleteMessage([
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                            break;
                        default:
                            if($check_history){
                                if($check_history->callback_data == 'cancel'){
                                    if(mb_strlen($text) > 10){
                                        $doc = PlmNotificationsList::findOne($check_history->doc_id);
                                        if($doc){
                                            $doc->add_info = $text;
                                            $doc->updated_at = time();
                                            $doc->updated_by = $user->id;
                                            $doc->status_id = $doc::STATUS_REJECTED;
                                            if($doc->save()){
                                                $check_history->delete();
                                                Request::sendMessage([
                                                    'chat_id' => $chat_id,
                                                    'text' => Yii::t('app', "Dokument rad etildi.")."\n".Yii::t('app', "Dokumentlarni ko'rish uchun /tekshirish ni bosing."),
                                                    'reply_markup' => Keyboard::remove(['selective' => true])
                                                ]);
                                            }else{
                                                Request::sendMessage([
                                                    'chat_id' => $chat_id,
                                                    'text' => 'Xatolik yuz berdi. Dokumentlarga qaytish uchun /tekshirish bosingiz.',
                                                    'reply_markup' => Keyboard::remove(['selective' => true])
                                                ]);
                                            }
                                        }
                                    }else{
                                        Request::sendMessage([
                                            'chat_id' => $chat_id,
                                            'text' => "Izoh uzunligi 10 ta belgidan ko'p bo'lishi kerak!",
                                        ]);
                                    }
                                }
                            }
                    }
                }
                if(isset($request['callback_query'])){
                    $text = $request['callback_query']['data'];
                    $message_id = $request['callback_query']['message']['message_id'];
                    $array_text = explode('_', $text);
                    switch ($array_text[0]){
                        case 'index':
                            $doc_id = (int)$array_text[1];
                            if(!empty($doc_id)){
                                $model = PlmNotificationsList::getViews($doc_id, $user->id);
                                echo "<pre>";
                                if(!empty($model)){
                                    $client = Client::getInstance();
                                    $client->getEngine()->setPath('/usr/local/bin/phantomjs');
                                    $width  = 800;
                                    $height = 400;
                                    $top    = 0;
                                    $left   = 0;

                                    $phantomjs = $client->getMessageFactory()->createCaptureRequest("http://plm.ru/uz/plm/plm-notifications-list/view-bot?id={$doc_id}&user_id={$user->id}", 'GET');
                                    $filename = "file_".time();
                                    $phantomjs->setOutputFile("uploads/{$filename}.jpg");
                                    $phantomjs->setViewportSize($width, $height);
                                    $phantomjs->setCaptureDimensions($width, $height, $top, $left);

                                    $response = $client->getMessageFactory()->createResponse();
                                    $client->send($phantomjs, $response);
//                                $url = "http://".$_SERVER['HTTP_HOST']."/uploads/{$filename}.jpg";
                                    $photo_url = "https://bfab-213-230-116-127.eu.ngrok.io/uploads/{$filename}.jpg";
                                    $keyboard = [
                                        [['text'=> "✅ ".Yii::t('app', "Tasdiqlash"),'callback_data'=> 'confirm_'.$doc_id],
                                            ['text'=> "❌ ".Yii::t('app', 'Rad qilish'),'callback_data'=> 'cancel_'.$doc_id],]

                                    ];
                                    if(is_file(Yii::getAlias('@web').'/uploads/'.$filename.'.jpg')){
                                        echo "<pre>";
                                        var_dump($photo_url);die;
                                    }
                                    $titleModel =
                                        "<code>Bo'lim:</code> : {$model['department']}\n".
                                        "<code>Hujjat raqami : </code>{$model['doc_number']}\n".
                                        "<code>Hujjat sanasi : </code>{$model['reg_date']}\n".
                                        "<code>Defektlar : </code>{$model['defect']}\n".
                                        "<code>Defektlar soni : </code>{$model['defect_count']}\n".
                                        "<code>Smena : </code>{$model['shift']}\n".
                                        "<code>Mahsulot : </code>{$model['product']}\n".
                                        "<code>Partiya raqami : </code>{$model['part_number']}\n".
                                        "<code>Uskunalar : </code>{$model['equipment']}\n".
                                        "<code>Izoh : </code>{$model['add_info']}\n".
                                        "<code>Miqdori : </code>{$model['fact_qty']}\n";
                                    $res = Request::sendPhoto([
                                        'chat_id' => $chat_id,
                                        'photo' => $photo_url,
                                        'caption' => $titleModel,
                                        'parse_mode' => 'HTML',
                                        'reply_markup' => json_encode([
                                            'inline_keyboard'=>$keyboard,
                                        ])
                                    ]);
                                    if(is_file(Yii::$app->basePath . "/web/uploads/{$filename}.jpg")){
                                        unlink(Yii::$app->basePath . "/web/uploads/{$filename}.jpg");
                                    }
                                }else{
                                    Request::sendMessage([
                                        'chat_id' => $chat_id,
                                        'text' => Yii::t('app', "Ma'lumotlar topilmadi"),
                                        'parse_mode' => 'HTML',
                                    ]);
                                }
                            }else{
                                Request::sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => Yii::t('app', 'Dokument topilmadi'),
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            break;
                        case 'confirm':
                            $doc_id = (int)$array_text[1];
                            $model = PlmNotificationsList::findOne($doc_id);
                            $docList = PlmNotificationsList::getViews($doc_id, $user->id);
                            if($model){
                                $no_defect = (($docList['token'] == 'UNPLANNED') || ($docList['token'] == 'PLANNED'));
                                $flag = true;
                                if($no_defect){
                                    $flag = false;
                                    $reasons = Reasons::getCategoryList($docList['category_id']);
                                    if($reasons){
                                        $poll = [];
                                        $transaction = Yii::$app->db->beginTransaction();
                                        $saved = false;
                                        $text = Yii::t('app', 'Hatolik yuz berdi');
                                        $data = [
                                            'chat_id' => $chat_id,
                                            'parse_mode' => 'HTML',
                                        ];
                                        try {
                                            $telegram_history = new TelegramHistory();
                                            $telegram_history->setAttributes([
                                                'chat_id' => $chat_id,
                                                'callback_data' => $array_text[0],
                                                'doc_id' => $doc_id,
                                                'users_id' => $user->id,
                                                'created_at' => time(),
                                            ]);
                                            if($telegram_history->save()){
                                                foreach ($reasons as $key => $reason){
                                                    $poll[] = $reason['name'];
                                                    $telegram_history_items = new TelegramHistoryItems();
                                                    $telegram_history_items->setAttributes([
                                                        'telegram_history_id' => $telegram_history->chat_id,
                                                        'item_id' => $reason['id'],
                                                        'key' => $key,
                                                        'doc_id' => $doc_id,
                                                    ]);
                                                    if($telegram_history_items->save()){
                                                        $saved = true;
                                                    }else{
                                                        $text = json_encode($telegram_history_items->getErrors(),JSON_PRETTY_PRINT);
                                                        $saved = false;
                                                        break;
                                                    }
                                                }
                                            }else{
                                                $text = json_encode($telegram_history->getErrors(),JSON_PRETTY_PRINT);
                                            }
                                            if($saved) {
                                                $transaction->commit();
                                                $result = Request::sendPoll([
                                                    'chat_id' => $chat_id,
                                                    'question' => Yii::t('app', "Tasdiqlanadigan to'xtalishlar ro'yhati"),
                                                    'is_anonymous' => false,
                                                    'type' => "regular",
                                                    'allows_multiple_answers' => true,
                                                    'options' => json_encode($poll,JSON_PRETTY_PRINT)
                                                ]);
                                                print_r($poll);
                                                print_r($result);
                                            }else{
                                                $transaction->rollBack();
                                            }
                                        } catch (\Exception $e) {
                                            $text = $e->getMessage();
                                            $transaction->rollBack();
                                        }
                                        if($saved == false){
                                            $data['text'] = $text;
                                            Request::sendMessage($data);
                                        }
                                    }else{
                                        Request::sendMessage([
                                            'chat_id' => $chat_id,
                                            'text'  => Yii::t("app", "Tasdiqlash uchun to'xtalishlar ro'yhati mavjud emas, Tasdiqlaysizmi ?"),
                                            'reply_markup' => json_encode([
                                                'inline_keyboard'=>[
                                                    [
                                                        ['text'=> "✅ ".Yii::t('app', 'Tasdiqlash'),'callback_data'=> 'confirmDoc_'.$doc_id],
                                                        ['text'=> "❌ ".Yii::t('app', "Bekor qilish"),'callback_data'=> 'cancelDoc_'.$doc_id],
                                                    ],
                                                ],
                                            ]),
                                        ]);
                                    }
                                }
                                if($flag){
                                    $this->confirmModel(['model'=>$model, 'chat_id'=>$chat_id, 'request'=>new Request()]);
                                }
                            }else{
                                Request::sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => Yii::t('app', 'Dokument topilmadi'),
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            echo print_r($model);
                            break;
                        case 'confirmDoc':
                            $doc_id = (int)$array_text[1];
                            $model = PlmNotificationsList::findOne($doc_id);
                            if($model){
                                $this->confirmModel(['model'=>$model, 'chat_id'=>$chat_id, 'request'=>new Request()]);
                            }else{
                                Request::sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => Yii::t('app', 'Dokument topilmadi'),
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            break;
                        case 'cancelDoc':
                            Request::deleteMessage([
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                        case 'cancel':
                            $doc_id = (int)$array_text[1];
                            $model = PlmNotificationsList::findOne($doc_id);
                            if($model){
                                if(($model->status_id < BaseModel::STATUS_ACCEPTED) && ($model->status_id != BaseModel::STATUS_INACTIVE)){
                                    $telegram_history = new TelegramHistory();
                                    $telegram_history->setAttributes([
                                        'chat_id' => $chat_id,
                                        'callback_data' => $array_text[0],
                                        'doc_id' => $doc_id,
                                        'users_id' => $user->id,
                                        'created_at' => time(),
                                    ]);
                                    if($telegram_history->save()){
                                        $res = Request::sendMessage([
                                            'chat_id' => $chat_id,
                                            'text' => "❌ ".Yii::t('app', 'Rad etish uchun izoh yozing'),
                                            'parse_mode' => 'HTML',
                                            'reply_markup' => json_encode([
                                                'keyboard' => [
                                                    [[
                                                        'text'=> "❌ ".Yii::t('app', 'Rad qilish'),
                                                        'data' => 'cancel_confirm',
                                                    ]],
                                                    [[
                                                        'text'=> "⏪ Dokumentlarga qaytish",
                                                        'data' => 'back_index',
                                                    ]]
                                                ],
                                                'remove_keyboard' => true,
                                            ]),
                                        ]);
                                    }else{
                                        Request::sendMessage([
                                            'chat_id' => $chat_id,
                                            'text' => Yii::t('app', 'Xatolik')."\n".Yii::t('app', "Dokumentlarni ko'rish uchun /tekshirish ni bosing"),
                                            'parse_mode' => 'HTML',
                                        ]);
                                    }
                                }else{
                                    Request::sendMessage([
                                        'chat_id' => $chat_id,
                                        'text' => Yii::t('app', 'Dokument tasdiqlangan yoki mavjud emas')."\n".Yii::t('app', "Dokumentlarni ko'rish uchun /tekshirish ni bosing"),
                                        'parse_mode' => 'HTML',
                                    ]);
                                }
                            }
                            else{
                                Request::sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => Yii::t('app', 'Dokument topilmadi')."\n".Yii::t('app', "Dokumentlarni ko'rish uchun /tekshirish ni bosing"),
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            isset($res) ?? print_r($res);
                            break;
                        case 'cancelDoc':
                            $doc_id = (int)$array_text[1];
                            $doc = PlmNotificationsList::findOne($doc_id);
                            if($doc){
                                $doc->status_id = PlmNotificationsList::STATUS_REJECTED;
                                if($doc->save()){
                                    Request::sendMessage([
                                        'chat_id' => $chat_id,
                                        'text' => Yii::t('app', 'Dokument bekor qilindi')."\n".Yii::t('app', "Dokumentlar ro'yxatini ko'rish uchun /tekshirish ni bosing"),
                                        'parse_mode' => 'HTML',
                                    ]);
                                }
                                else{
                                    Request::sendMessage([
                                        'chat_id' => $chat_id,
                                        'text' => Yii::t('app', 'Dokument bekor qilinmadi'),
                                        'parse_mode' => 'HTML',
                                    ]);
                                }
                            }
                            else{
                                Request::sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => Yii::t('app', 'Dokument topilmadi'),
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            echo print_r($doc);
                            break;
                    }
                }
                if(isset($request['poll_answer'])){
                    $poll_answer = $request['poll_answer'];
                    $option_ids = $poll_answer['option_ids'];
                    $transaction = Yii::$app->db->beginTransaction();
                    $saved = true;
                    $data = [
                        'chat_id' => $chat_id,
                        'text' => Yii::t('app', 'Dokument muvaffaqiyatli tasdiqlandi'),
                        'parse_mode' => 'HTML',
                    ];
                    try {
                        $telegram_history = TelegramHistory::findOne(['chat_id' => $chat_id]);
                        $telegram_history_items = TelegramHistoryItems::find()->where(['key' => $option_ids, 'telegram_history_id' => $telegram_history->chat_id])->all();
                        $model = PlmNotificationsList::findOne($telegram_history->doc_id);
                        if(!empty($telegram_history_items) && $model){
                            if(($model->status_id < BaseModel::STATUS_ACCEPTED) && ($model->status_id != BaseModel::STATUS_INACTIVE)){
                                $model->status_id = BaseModel::STATUS_ACCEPTED;
                                if($model->save()){
                                    foreach ($telegram_history_items as $reason){
                                        $plmNotificationRelReason = new PlmNotificationsListRelReason();
                                        $plmNotificationRelReason->plm_notification_list_id = $model->id;
                                        $plmNotificationRelReason->reason_id = $reason['item_id'];
                                        $plmNotificationRelReason->status_id = BaseModel::STATUS_ACTIVE;
                                        if(!$plmNotificationRelReason->save()){
                                            $saved = false;
                                            $data['text'] = json_encode($plmNotificationRelReason->errors,JSON_PRETTY_PRINT);
                                            break;
                                        }
                                    }
                                }else{
                                    $data['text'] = json_encode($model->errors,JSON_PRETTY_PRINT);
                                    $saved = false;
                                }
                            }
                        }
                        if($saved){
                            $telegram_history->delete();
                        }
                        if($saved) {
                            $transaction->commit();
                        }else{
                            $transaction->rollBack();
                        }
                    } catch (\Exception $e) {
                        $saved = false;
                        $data['text'] = $e->getMessage();
                        $transaction->rollBack();
                    }
                    if($saved){
                        $text = "\n Boshqa tasdiqlanmagan dokumentlarni ko'rish uchun /tekshirish ni bosing";
                    }else{
                        $text = "\n Dokument tasdiqlanmadi! Tasdiqlanmagan dokumentlarni ko'rish uchun /tekshirish ni bosing";
                    }
                    $data['text'] = $data['text'].$text;
                    Request::sendMessage($data);
                }
            }else{
                Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Sizga tegishli foydalanuvchi topilmadi'
                ]);
            }
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            Telegram::sendMultiple([Telegram::DOSTON], json_encode($e->getMessage(), JSON_PRETTY_PRINT));
        }
    }

    /**
     * @param array $params
     * @return void
     */
    public function confirmModel(array $params){
        $model = $params['model'];
        $chat_id = $params['chat_id'];
        $request = $params['request'];
        if(($model->status_id < BaseModel::STATUS_ACCEPTED) && ($model->status_id != BaseModel::STATUS_INACTIVE)){
            $model->status_id = PlmNotificationsList::STATUS_ACCEPTED;
            if($model->save()){
                $request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "✅ ".Yii::t('app', 'Dokument tasdiqlandi')."\n".Yii::t('app', "Dokumentlar ro'yxatini ko'rish uchun /tekshirish ni bosing"),
                    'parse_mode' => 'HTML',
                ]);
            }
            else{
                $request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => Yii::t('app', 'Dokument tasdiqlanmadi'),
                    'parse_mode' => 'HTML',
                ]);
            }
        }
        else{
            $request::sendMessage([
                'chat_id' => $chat_id,
                'text' => Yii::t('app', 'Dokument tasdiqlangan yoki mavjud emas'),
                'parse_mode' => 'HTML',
            ]);
        }
    }
    public function sendWork($params){
        $work = $this->getWork($params['user_id']);
        if($params['Request'] && $params['chat_id']){
            if(is_iterable($work) && !empty($work)){
                $keyboard = [];
                foreach ($work as $item) {
                    $keyboard[] = [
                        ['text'=> "{$item['department']} - {$item['doc_number']} - {$item['reg_date']} - {$item['equipment']} - {$item['category_name']} - {$item['department']}",'callback_data'=> 'index_'.$item['id']]
                    ];
                }
                $result = $params['Request']::sendMessage([
                    'chat_id' => $params['chat_id'],
                    'text' => Yii::t('app', "Tasdiqlanmagan dokumentlar ro'yxati"),
                    'reply_markup' => Keyboard::remove()
                ]);
                $result = $params['Request']::sendMessage([
                    'chat_id' => $params['chat_id'],
                    'text' => Yii::t('app', 'Tasdiqlash uchun tanlang'),
                    'reply_markup' => Keyboard::remove([
                        'inline_keyboard' => $keyboard
                    ])
                ]);
                print_r($result);
            }else{
                $params['Request']::sendMessage([
                    'chat_id' => $params['chat_id'],
                    'text' => Yii::t('app', 'Tasdiqlanadigan dokumentlar topilmadi'),
                ]);
            }
        }
    }
    public function getWork($user_id)
    {
        if($user_id){
            $query = PlmSectorRelHrDepartment::find()
                ->alias('psrd')
                ->select([
                    'pnl.id',
                    'pd.reg_date',
                    'hd.name AS department',
                    'sh.name shift',
                    'product.product',
                    'equipment.equipment',
                    'pnl.defect_type_id',
                    'pnl.begin_time',
                    'pnl.end_time',
                    'defect.defect',
                    'defect.count AS defect_count',
                    'pnl.status_id',
                    'c.token',
                    'c.name_uz as category_name',
                    'pd.doc_number',
                ])
                ->leftJoin(['pnl' => 'plm_notifications_list'], 'pnl.category_id = psrd.category_id')
                ->leftJoin(['ps' => 'plm_stops'], 'pnl.stop_id = ps.id')
                ->leftJoin(['pdi' => 'plm_document_items'], 'pnl.plm_doc_item_id = pdi.id')
                ->leftJoin(['pd' => 'plm_documents'], 'pdi.document_id = pd.id')
                ->leftJoin(['sh' => 'shifts'], 'pd.shift_id = sh.id')
                ->leftJoin(['hd' => 'hr_departments'], 'pd.hr_department_id = hd.id')
                ->leftJoin(['c' => 'categories'], 'pnl.category_id = c.id')
                ->leftJoin(['defect' => PlmNotificationRelDefect::find()
                    ->alias('pnrd')
                    ->select([
                        'pnrd.plm_notification_list_id',
                        'SUM(pnrd.defect_count) AS count',
                        "STRING_AGG(DISTINCT d.name_uz,', ') AS defect"
                    ])
                    ->leftJoin(['d' => 'defects'], 'pnrd.defect_id = d.id')
                    ->groupBy(['pnrd.plm_notification_list_id'])
                ], 'defect.plm_notification_list_id = pnl.id')
                ->leftJoin(['product' => PlmDocItemProducts::find()
                    ->alias('pdip')
                    ->select([
                        "pdip.document_item_id",
                        "STRING_AGG(DISTINCT p.name,', ') AS product",
                    ])
                    ->leftJoin(['p' => 'products'], 'pdip.product_id = p.id')
                    ->groupBy(['pdip.document_item_id'])
                ], 'product.document_item_id = pnl.plm_doc_item_id')
                ->innerJoin(['equipment' => PlmDocItemEquipments::find()
                    ->alias('pdie')
                    ->select([
                        "pdi.id as pdi_id",
                        "STRING_AGG(DISTINCT e.name,', ') AS equipment",
                    ])
                    ->innerJoin(['e' => 'equipments'], 'e.id = pdie.equipment_id')
                    ->leftJoin(['pdi' => 'plm_document_items'], 'pdi.id = pdie.document_item_id')
                    ->groupBy(['pdi.id'])
                ], 'equipment.pdi_id = pdi.id');
            $hr_department = HrEmployeeRelPosition::getActiveHrDepartment($user_id);
            $query = $query->andWhere(['=', 'psrd.hr_department_id', $hr_department['hr_department_id']]);


            $query = $query->andFilterWhere(['IN', 'hd.id', UsersRelationHrDepartments::getDepartmentByUser(1,$user_id)]);
            $query = $query->andFilterWhere(['pnl.status_id' => $user_id]);
            return $query->all();
        }
    }
}

