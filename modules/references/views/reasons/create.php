<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Reasons */

$this->title = Yii::t('app', 'Create Reasons');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reasons-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
