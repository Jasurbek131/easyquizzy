<?php

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrPositions;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use unclead\multipleinput\TabularInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $form yii\widgets\ActiveForm */
/* @var $hrEmployeeRelPosition app\modules\hr\models\HrEmployeeRelPosition[]*/

?>

<div class="hr-employee-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'id')->hiddenInput()->label(false)?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status_id')->dropDownList(\app\models\BaseModel::getStatusList()) ?>
        </div>
    </div>
    <div class="box box-solid box-primary">
        <div class="box-header"><?= Yii::t('app', 'Hodimga bo\'lim va lavozim biriktirish') ?></div>
        <div class="box-body">
            <?= TabularInput::widget([
                'id' => 'hr_employee_rel_position',
                'models' => $hrEmployeeRelPosition,
                'theme' => 'bs',
                'iconSource' => TabularInput::ICONS_SOURCE_FONTAWESOME,
                'addButtonPosition' => TabularInput::POS_HEADER,
                'addButtonOptions' => [
                    'class' => 'btn btn-xs btn-primary'
                ],
                'removeButtonOptions' => [
                    'class' => 'btn btn-xs btn-danger'
                ],
                'cloneButton' => false,
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'hr_employee_id',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'hr_department_id',
                        'title' => Yii::t('app', 'Hr Department'),
                        'type' => Select2::class,
                        'options' => function ($data) {
                            return [
                                'data' => HrDepartments::getList(),
                                'pluginOptions' => [
                                    'placeholder' => Yii::t('app', 'Hr Departments'),
                                    'allowClear' => true,
                                ],
                                'options' => [
//                                    'readonly' => $data->status_id == \app\models\BaseModel::STATUS_SAVED,
                                    'disabled' => $data->status_id == \app\models\BaseModel::STATUS_INACTIVE
                                ]
                            ];
                        },
                        'headerOptions' => [
                            'style' => 'width:25%;'
                        ]
                    ],
                    [
                        'name' => 'hr_position_id',
                        'title' => Yii::t('app', 'Hr Position'),
                        'type' => Select2::class,
                        'options' => function ($data) {
                            return [
                                'data' => HrPositions::getList(),
                                'pluginOptions' => [
                                    'placeholder' => Yii::t('app', 'Hr Position'),
                                    'allowClear' => true,
                                ],
                                'options' => [
//                                    'readonly' => $data->status_id == \app\models\BaseModel::STATUS_SAVED
                                    'disabled' => $data->status_id == \app\models\BaseModel::STATUS_INACTIVE
                                ]
                            ];
                        },
                        'headerOptions' => [
                            'style' => 'width:25%;'
                        ]
                    ],
                    [
                        'name' => 'begin_date',
                        'title' => Yii::t('app', 'Begin Date'),
                        'type' => DatePicker::class,
                        'options' => function ($data) {
                            return [
                                'data' => date('d.m.Y',($data->begin_date)),
                                'removeButton' => false,
                                'options' => [
                                        'autocomplete' => 'off',
                                        'readonly' => $data->status_id == \app\models\BaseModel::STATUS_INACTIVE
                                ],
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy'
                                ]
                            ];
                        },
                        'headerOptions' => [
                            'style' => 'width:20%;'
                        ]
                    ],
                    [
                        'name' => 'end_date',
                        'title' => Yii::t('app', 'End Date'),
                        'type' => DatePicker::class,
                        'options' => function ($data) {
                            return [
                                'removeButton' => false,
                                'options' => [
                                    'autocomplete' => 'off',
                                    'readonly' => $data->status_id == \app\models\BaseModel::STATUS_INACTIVE
                                ],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy'
                                ]
                            ];
                        },
                        'headerOptions' => [
                            'style' => 'width:20%;'
                        ]
                    ],
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t("app","Save"), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
