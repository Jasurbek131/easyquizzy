<?php

use app\models\BaseModel;
use app\modules\references\models\Currency;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card">
    <div class="card-body">
        <div class="products-form">

            <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

            <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($model, 'part_number')->textInput(['maxlength' => true]) ?>

<!--            <div class="row">-->
<!--                <div class="col-lg-6">-->
<!--                    --><?php //echo $form->field($model, 'scrapped_price')->textInput([
//                        'type' => 'number',
//                        'step' => '0.001'
//                    ]) ?>
<!--                </div>-->
<!--                <div class="col-lg-6">-->
<!--                    --><?php //echo $form->field($model, 'scrapped_currency_id')->dropDownList(
//                        Currency::getList(true)
//                    ) ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="row">-->
<!--                <div class="col-lg-6">-->
<!---->
<!--                    --><?php //echo $form->field($model, 'repaired_price')->textInput([
//                        'type' => 'number',
//                        'step' => '0.001'
//                    ]) ?>
<!--                </div>-->
<!--                <div class="col-lg-6">-->
<!--                    --><?php //echo $form->field($model, 'repaired_currency_id')->dropDownList(Currency::getList(true)) ?>
<!--                </div>-->
<!--            </div>-->

            <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

            <div class="form-group">
                <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success button-save-form']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
