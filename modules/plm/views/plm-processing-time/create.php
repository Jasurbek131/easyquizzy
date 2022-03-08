<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmProcessingTime */

$this->title = Yii::t('app', 'Create Plm Processing Time');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Processing Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plm-processing-time-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
