<?php

use app\modules\references\models\Equipments;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'equipments')->widget(Select2::class, [
        'data' => Equipments::getListForSelect(true),
        'options' => ['placeholder' => Yii::t('app','Select')],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ]
    ]) ?>

    <?php echo $form->field($model, 'part_number')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success button-save-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
