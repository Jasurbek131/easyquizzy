<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Categories */

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
