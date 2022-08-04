<?php

/* @var $permission*/
/* @var $perms*/
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthItem */

$this->title = Yii::t('app', 'Update Auth Items: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-items-update">

    <?= $this->render('_form', [
        'model' => $model,
        'perms' => $perms,
        'permission' => $permission,
    ]) ?>

</div>
