<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthItem */

$this->title = Yii::t('app', 'Create Auth Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-items-create">

    <?= $this->render('_form', [
        'model' => $model,
        'perms' => $perms,
        'permission' => $permission,
    ]) ?>

</div>
