<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Products */

$this->title = Yii::t('app', 'Update Products: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="products-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
