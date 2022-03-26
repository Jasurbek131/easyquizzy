<?php

use app\assets\AppAsset;
use app\assets\ReactAsset;
use yii\web\View;

/* @var $this View */
$this->title = Yii::t('app', 'Stops');
$this->params['breadcrumbs'][] = $this->title;

ReactAsset::$reactFileName = 'modules/plm/report/plmStopReport';
ReactAsset::$reactCssFileName = 'stop';
ReactAsset::register($this);
$this->params['bodyClass'] = "sidebar-collapse";
?>
    <div id="root"></div>

<?php
$this->registerCssFile('/css/loader/contextLoader.min.css', ['depends' => AppAsset::class]);
