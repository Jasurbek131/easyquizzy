<?php

use app\models\BaseModel;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\Products;
use app\modules\references\models\TimeTypesList;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\ProductLifecycle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-lifecycle-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'product_id')->widget(Select2::class, [
        'data' => Products::getList(),
        'options' => ['placeholder' => Yii::t('app', 'Select')],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'equipment_group_id')->widget(Select2::class, [
        'data' => EquipmentGroup::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ..."),],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php echo $form->field($model, 'lifecycle')->input('number', ['class' => 'form-control']) ?>

    <?php echo $form->field($model, 'bypass')->input('number', ['class' => 'form-control']) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
