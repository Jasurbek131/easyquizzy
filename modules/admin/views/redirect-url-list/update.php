<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RedirectUrlList */

$this->title = Yii::t('app', 'Update Redirect Url List: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Redirect Url Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="redirect-url-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
