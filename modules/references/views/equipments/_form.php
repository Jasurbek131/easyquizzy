<?php

use app\modules\references\models\EquipmentTypes;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Equipments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipments-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'equipment_type_id')->widget(Select2::classname(), [
            'data' => EquipmentTypes::getList(),
            'options' => ['placeholder' => 'Select ...'],
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
