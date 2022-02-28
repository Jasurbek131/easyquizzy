<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\TimeTypesList */

$this->title = Yii::t('app', 'Create Time Types List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Time Types Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-types-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
