<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\TimeTypesList */

$this->title = Yii::t('app', 'Update Time Types List: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Time Types Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="time-types-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
