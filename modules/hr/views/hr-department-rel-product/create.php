<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelProduct */

$this->title = Yii::t('app', 'Create Hr Department Rel Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Rel Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-rel-product-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
