<?php

use app\components\TabularInput\CustomTabularInput;
use app\models\BaseModel;
use app\modules\references\models\Equipments;
use kartik\select2\Select2;
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

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?php echo CustomTabularInput::widget([
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
                    'name' => 'equipment_group_id',
                    'type' => "hiddenInput",
                    'defaultValue' => $model->id ?? 0
                ],
                [
                    'name' => 'status_id',
                    'type' => "hiddenInput",
                    'defaultValue' => BaseModel::STATUS_ACTIVE
                ],
                [
                    'name' => 'work_order',
                    'type' => "hiddenInput",
                    'defaultValue' => true
                ],
                [
                    'name' => 'equipment_id',
                    'type' => Select2::class,
                    'title' => Yii::t('app', 'Equipments'),
                    'options' => [
                        'data' => Equipments::getList(),
                        'options' => [
                            'prompt' => Yii::t('app', 'Select ...'),
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

    <?php echo $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status_id')->dropDownList(BaseModel::getStatusList()) ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$css = <<<CSS
.list-cell__equipment_id {
    padding-left: 0!important;
}
CSS;
$this->registerCss($css);
