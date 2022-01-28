<?php

use app\modules\admin\models\AuthItem;
use app\modules\hr\models\HrEmployee;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'hr_employee_id')->widget(Select2::class,[
        'data' => HrEmployee::getList(),
        'options' => [
            'placeholder' => Yii::t('app','Select')
        ]
    ]) ?>

    <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'roles')->widget(Select2::class,[
        'data' => AuthItem::getRoles(),
        'options' => [
            'placeholder' => Yii::t('app','Select')
        ],
        'pluginOptions' => [
            'multiple' => true
        ]
    ]) ?>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
