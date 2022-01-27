<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Shifts */

$this->title = Yii::t('app', 'Create Shifts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shifts-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
