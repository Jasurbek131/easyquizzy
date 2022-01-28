<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Equipments */

$this->title = Yii::t('app', 'Create Equipments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Equipments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
