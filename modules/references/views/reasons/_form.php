<?php

use app\modules\references\models\Categories;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Reasons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reasons-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
        'data' => Categories::getList(),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?> ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
