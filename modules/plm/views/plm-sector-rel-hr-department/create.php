<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmSectorRelHrDepartment */

$this->title = Yii::t('app', 'Create Plm Sector Rel Hr Department');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Sector Rel Hr Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plm-setting-accepted-sector-rel-hr-department-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
