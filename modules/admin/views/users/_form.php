<?php

use app\modules\admin\models\AuthItem;
use app\modules\admin\models\RedirectUrlList;
use app\modules\hr\models\HrEmployee;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $roles AuthItem */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->field($model, 'hr_employee_id')->widget(Select2::class, [
                'data' => HrEmployee::getList(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'id' => 'hr_employee_id',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'redirect_url_id')->widget(Select2::class, [
                'data' => RedirectUrlList::getList(true),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <?php
        /**
         * @var $employee HrEmployee
         */
        $employee = !empty($model->hrEmployees) ? $model->hrEmployees[0]->hrEmployee : "";
        ?>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'hr_organisation_name')->textInput(['id' => 'hr_organisation_name', 'readonly' => true, 'value' => $employee ? $employee->hrEmployeeRelPosition[0]->hrOrganisations->name : ""]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'hr_deparment_name')->textInput(['id' => 'hr_deparment_name', 'readonly' => true, 'value' => $employee ? $employee->hrEmployeeRelPosition[0]->hrDepartments->name : ""]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'phone_number')->textInput(['id' => 'phone_number', 'readonly' => true,  'value' => $employee ? $employee->phone_number : ""]) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'email')->textInput(['id' => 'email', 'readonly' => true,  'value' => $employee ? $employee->email : ""]) ?>
        </div>
    </div>

    <fieldset>
        <legend style="text-align: center"><?php echo Yii::t('app', 'Permissions')?>:</legend>
        <div class="container">
            <div class="row checkbox_container">
                <?php foreach ($roles as $key => $role) : ?>
                    <div class="col-lg-6">
                        <div class="form-group small">
                            <label class="checkbox-transform">
                                <input type="checkbox"
                                       name="Users[roles][<?php echo $key ?>]"
                                    <?php echo $model["roles"][$key] !== null ? "checked" : "" ?>
                                       class="checkbox">
                                <span class="checkmark"></span>
                                <span class="p-10"><?php echo $role ?></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </fieldset>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>

    fieldset .form-group {
        margin-bottom: 0.2rem !important;
    }

    fieldset {
        margin: 0 0 30px 0;
        border: 2px solid #ccc;
        border-radius: 2px;
    }

    legend {
        background: #eee;
        padding: 4px 10px;
        color: #000;
        max-width: 25% !important;
        margin: 0 auto;
        display: inline;
    }

    .p-10 {
        padding-left: 15px !important;
    }
</style>
<?php
$this->registerCssFile('/web/css/custom_checkbox.css')
?>
