<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\EquipmentTypes */

$this->title = Yii::t('app', 'Create Equipment Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Equipment Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
