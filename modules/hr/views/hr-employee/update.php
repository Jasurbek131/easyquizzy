<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $hrEmployeeRelPosition app\modules\hr\models\HrEmployeeRelPosition[]*/

$this->title = 'Update Hr Employee: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hr Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hr-employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'hrEmployeeRelPosition' => $hrEmployeeRelPosition,

    ]) ?>

</div>
