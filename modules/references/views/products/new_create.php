<?php

use app\assets\AppAsset;
use app\assets\ReactAsset;
use yii\web\View;

/* @var $this View */
$this->title = Yii::t('app', 'New product create');
$this->params['breadcrumbs'][] = $this->title;

ReactAsset::$reactFileName = 'product';
ReactAsset::$reactCssFileName = 'product';
ReactAsset::register($this);

?>
    <div id="root"></div>

<?php
$this->registerCssFile('/css/loader/contextLoader.min.css', ['depends' => AppAsset::class]);