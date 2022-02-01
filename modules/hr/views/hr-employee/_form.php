<?php

use app\modules\hr\models\HrDepartments;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-employee-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hr_department_id')->widget(Select2::classname(), [
        'data' => HrDepartments::getList(),
        'options' => ['placeholder' => 'Select ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'hr_position_id')->widget(Select2::classname(), [
        'data' => \app\modules\hr\models\HrPositions::getList(),
        'options' => ['placeholder' => 'Select ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
