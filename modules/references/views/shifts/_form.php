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

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'start_time')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => false,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 0,
        ]
    ]) ?>

    <?php echo $form->field($model, 'end_time')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => false,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 0,
        ]
    ]) ?>

    <?php echo $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
