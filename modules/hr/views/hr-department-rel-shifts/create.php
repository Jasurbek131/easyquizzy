<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelShifts */

$this->title = Yii::t('app', 'Create Hr Department Rel Shifts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Rel Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-rel-shifts-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
