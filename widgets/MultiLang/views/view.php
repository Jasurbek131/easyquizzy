<?php
namespace app\widgets\MultiLang;
use yii\helpers\Html;
use Yii;

$controller = Yii::$app->controller->id;
$module = Yii::$app->controller->module->id;
$action = Yii::$app->controller->action->id;
$slug = Yii::$app->request->get('slug');
$t = Yii::$app->request->get('t');
$id = Yii::$app->request->get('id');


if ($slug && $t){
    $url = "/$module/$controller/$slug/$action?t=$t";
}elseif($slug && $id){
    $url = "/$module/$controller/$slug/$action?id=$id";
}elseif($id){
    $url = "/$module/$controller/$action?id=$id";
}elseif($slug){
    $url = "/$module/$controller/$slug/$action";
}elseif ($t){
    $url = "/$module/$controller/$action?t=$t";
}else{
    $url = '';
}


?>


    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <span class="uppercase"><?=Html::img('/img/flags/'.Yii::$app->language.'.png', ['width'=>'20']) ?><?= '&nbsp;'.strtoupper(Yii::$app->language); ?></span>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li class="item-lang dropdown-item">
            <?= Html::a(Html::img('/img/flags/uz.png', ['width'=>'20']). ' UZ', [
                $url,
                'language' => 'uz'
            ]); ?>
        </li>
        <li class="item-lang dropdown-item">
            <?= Html::a(Html::img('/img/flags/ru.png', ['width'=>'20']). ' RU', [
                $url,
                'language' => 'ru'
            ]); ?>
        </li>
    </ul>
