<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPositions */

$this->title = Yii::t('app', 'Create Hr Positions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-positions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
