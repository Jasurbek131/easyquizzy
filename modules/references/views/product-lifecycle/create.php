<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\ProductLifecycle */

$this->title = Yii::t('app', 'Create Product Lifecycle');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Lifecycles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-lifecycle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
