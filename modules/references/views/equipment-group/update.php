<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\EquipmentGroup */
/* @var $models app\modules\references\models\EquipmentGroupRelationEquipment */

$this->title = Yii::t('app', 'Update Equipment Group: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Equipment Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="equipment-group-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
