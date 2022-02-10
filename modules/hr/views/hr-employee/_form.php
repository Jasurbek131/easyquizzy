<?php

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrPositions;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use unclead\multipleinput\TabularInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $form yii\widgets\ActiveForm */
/* @var $hrEmployeeRelPosition app\modules\hr\models\HrEmployeeRelPosition[]*/

?>

<div class="hr-employee-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'id')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->field($model, 'hr_department_id')->widget(Select2::class, [
                'data' => HrDepartments::getList(),
                'pluginOptions' => [
                    'placeholder' => Yii::t('app', 'Hr Departments'),
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'hr_position_id')->widget(Select2::class, [
                'data' => HrPositions::getList(),
                'pluginOptions' => [
                    'placeholder' => Yii::t('app', 'Hr Position'),
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model, 'begin_date')->widget( DatePicker::class,[
                'data' => $model->begin_date ? date('d.m.Y',($model->begin_date)) : $model->begin_date,
                'removeButton' => false,
                'options' => [
                    'autocomplete' => 'off',
                ],
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ])?>
        </div>
    </div>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t("app","Save"), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
