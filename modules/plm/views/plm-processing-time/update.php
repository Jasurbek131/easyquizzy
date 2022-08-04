<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmProcessingTime */

$this->title = Yii::t('app', 'Update Plm Processing Time: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Processing Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plm-processing-time-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
