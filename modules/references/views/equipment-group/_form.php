<?php

use app\modules\references\models\Equipments;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\EquipmentGroup */
/* @var $models app\modules\references\models\EquipmentGroupRelationEquipment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-group-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= \app\components\TabularInput\CustomTabularInput::widget([
            'id' => 'equipment_relation_equipment_group',
            'form' => $form,
            'models' => $models,
            'theme' => 'bs',
            'iconSource' => MultipleInput::ICONS_SOURCE_FONTAWESOME,
            'min' => 1,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'addButtonOptions' => [
                'class' => 'btn-primary btn btn-xs'
            ],
            'removeButtonOptions' => [
                'class' => 'btn btn-xs btn-danger'
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'name' => 'id',
                    'type' => "hiddenInput"
                ],
                [
                    'name' => 'equipment_group_id',
                    'type' => "hiddenInput",
                    'value' => $model->id
                ],
                [
                    'name' => 'equipment_id',
                    'type' => \kartik\select2\Select2::class,
                    'title' => Yii::t('app', 'Baski Desen'),
                    'options' => [
                        'data' => Equipments::getList(),
                        'options' => [
                            'prompt' => Yii::t('app', 'Baski Desen'),
                            'class' => 'toquv_ip_color_id'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression("function (markup) {return markup;}"),
                            'templateResult' => new JsExpression("function(data) { return data.text;}"),
                            'templateSelection' => new JsExpression("function (data) { return data.text; }"),
                        ],
                    ],
                ],
            ]
        ]) ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
