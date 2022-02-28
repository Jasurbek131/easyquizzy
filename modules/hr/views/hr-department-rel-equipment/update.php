<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelEquipment */

$this->title = Yii::t('app', 'Update Hr Department Rel Equipment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Rel Equipments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hr-department-rel-equipment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
