<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'part_number')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success button-save-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
