<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelDefects */

$this->title = Yii::t('app', 'Create Hr Department Rel Defects');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Rel Defects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-rel-defects-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
