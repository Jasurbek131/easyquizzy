<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $hrEmployeeRelPosition app\modules\hr\models\HrEmployeeRelPosition[]*/
$this->title = 'Create Hr Employee';
$this->params['breadcrumbs'][] = ['label' => 'Hr Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employee-create">

    <?= $this->render('_form', [
        'model' => $model,
        'hrEmployeeRelPosition' => $hrEmployeeRelPosition,
    ]) ?>

</div>
