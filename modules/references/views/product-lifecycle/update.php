<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\ProductLifecycle */

$this->title = Yii::t('app', 'Update Product Lifecycle: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Lifecycles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="product-lifecycle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
