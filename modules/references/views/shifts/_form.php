<?php

use kartik\time\TimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\references\models\BaseModel;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Shifts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shifts-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_time')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => false,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 0,
        ]
    ]) ?>

    <?= $form->field($model, 'end_time')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => false,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 0,
        ]
    ]) ?>

<!--    --><?php //= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
