<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\references\models\BaseModel;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\TimeTypesList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-types-list-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->input('number', ['class' => 'form-control number']) ?>

    <?= $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
