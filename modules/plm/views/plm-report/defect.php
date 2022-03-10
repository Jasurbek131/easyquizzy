<?php

use app\assets\AppAsset;
use app\assets\ReactAsset;
use yii\web\View;

/* @var $this View */
$this->title = Yii::t('app', 'OEE');
$this->params['breadcrumbs'][] = $this->title;

ReactAsset::$reactFileName = 'modules/plm/report/plmDefectReport';
ReactAsset::$reactCssFileName = 'defect';
ReactAsset::register($this);
$this->params['bodyClass'] = "sidebar-collapse";
?>
    <div id="root"></div>

<?php
$this->registerCssFile('/css/loader/contextLoader.min.css', ['depends' => AppAsset::class]);
