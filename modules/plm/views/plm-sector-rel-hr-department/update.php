<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmSectorRelHrDepartment */

$this->title = Yii::t('app', 'Update Plm Sector Rel Hr Department: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Sector Rel Hr Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plm-setting-accepted-sector-rel-hr-department-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
