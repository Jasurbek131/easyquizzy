<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartments */

$this->title = 'Create Hr Departments';
$this->params['breadcrumbs'][] = ['label' => 'Hr Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-departments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
