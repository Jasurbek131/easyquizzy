<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\EquipmentGroup */
/* @var $models app\modules\references\models\EquipmentGroupRelationEquipment */

$this->title = Yii::t('app', 'Create Equipment Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Equipment Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-group-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
