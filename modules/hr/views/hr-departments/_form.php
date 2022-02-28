<?php

use app\modules\hr\models\HrDepartments;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-departments-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php 
        if(!empty($model->parent_id)){
            echo $form->field($model, 'parent_id')->widget(Select2::class, [
                'data' => HrDepartments::getList(),
                'options' => [
                    'placeholder' => Yii::t("app","Select ..."),
                    'disabled' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ])->label(Yii::t("app","Parent ID"));
        }
    ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-success button-save-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
