<?php

use app\modules\hr\models\HrDepartments;
use app\modules\plm\models\PlmSectorList;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmSettingAcceptedSectorRelHrDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plm-setting-accepted-sector-rel-hr-department-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'hr_department_id')->widget(Select2::classname(), [
        'data' => HrDepartments::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'plm_sector_list_id')->widget(Select2::classname(), [
        'data' => PlmSectorList::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
