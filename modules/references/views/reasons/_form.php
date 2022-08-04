<?php

use app\models\BaseModel;
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

    <?= $form->field($model, 'category_id')->widget(Select2::class, [
        'data' => Categories::getList(true,[
            'token' => [Categories::TOKEN_PLANNED, Categories::TOKEN_UNPLANNED]
        ]),
        'options' => ['placeholder' => Yii::t("app","Select ...")],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
