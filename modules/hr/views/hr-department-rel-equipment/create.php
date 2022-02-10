<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelEquipment */

$this->title = Yii::t('app', 'Create Hr Department Rel Equipment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Rel Equipments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-rel-equipment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
