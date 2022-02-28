<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $roles \app\modules\admin\models\AuthItem */

$this->title = Yii::t('app', 'Update Users: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ]) ?>

</div>
