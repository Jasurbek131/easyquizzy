<?php

use app\assets\EmptyAsset;
use yii\helpers\Html;
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 03.03.20 13:43
 */
/* @var $this \yii\web\View */

/* @var $content string */
EmptyAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php $this->registerCsrfMetaTags() ?>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
        <title><?= Html::encode($this->title)??Yii::t('app','Samo') ?></title>
        <?php $this->head() ?>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    </head>
    <body>
    <?php $this->beginBody() ?>
    <?=$content?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>