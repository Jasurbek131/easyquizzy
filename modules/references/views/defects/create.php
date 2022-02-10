<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Defects */

$this->title = Yii::t('app', 'Create Defects');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Defects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defects-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
