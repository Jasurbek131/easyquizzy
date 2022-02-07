<?php

use app\assets\AppAsset;
use app\assets\ReactAsset;
use yii\web\View;

/* @var $this View */
$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;

ReactAsset::$reactFileName = 'document';
ReactAsset::$reactCssFileName = 'document';
\app\assets\ReactAsset::register($this);

?>
    <div id="root"></div>

<?php
$this->registerCssFile('/css/loader/contextLoader.min.css', ['depends' => AppAsset::className()]);
