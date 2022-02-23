<?php

use app\models\BaseModel;
use app\modules\hr\models\HrDepartments;
use app\modules\references\models\Categories;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmSectorRelHrDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plm-setting-accepted-sector-rel-hr-department-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'hr_department_id')->widget(Select2::class, [
        'data' => HrDepartments::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php echo $form->field($model, 'categories')->widget(Select2::class, [
        'data' => Categories::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ]
    ]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
