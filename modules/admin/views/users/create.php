<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $roles \app\modules\admin\models\AuthItem */
/* @var $role_types \app\modules\admin\models\AuthItem */

$this->title = Yii::t('app', 'Create Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-create">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'role_types' => $role_types,
    ]) ?>

</div>
