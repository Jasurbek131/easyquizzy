<?php

use app\modules\hr\models\HrDepartments;
use app\modules\references\models\Products;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentRelProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-department-rel-product-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php
    if(!empty($model->hr_department_id)){
        echo $form->field($model, 'hr_department_id')->widget(Select2::class, [
            'data' => HrDepartments::getList(),
            'options' => [
                'placeholder' => 'Select ...',
                'disabled' => true
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label(Yii::t("app","Hr Department"));
    }
    ?>

    <?= $form->field($model, 'product_id')->widget(Select2::class, [
        'data' =>Products::getList(),
        'options' => [
            'placeholder' => 'Select ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label(Yii::t("app","Products")) ?>

    <?= $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success button-save-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
