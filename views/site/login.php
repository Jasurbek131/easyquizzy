<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = Yii::t('app','Dataprizma-PLM');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login container" style="min-height: 70vh; display: flex; justify-content: center; align-items: center">
    <div>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>

        <?php echo $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?php echo $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <div class="col-lg-12">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
