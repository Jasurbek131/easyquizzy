<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RedirectUrlList */

$this->title = Yii::t('app', 'Create Redirect Url List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Redirect Url Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redirect-url-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
