<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 21.01.20 20:53
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\admin\models\AuthItem */
/* @var $models array */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html; ?>
<div class="auth-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="auth-item-form">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model,'name')->textInput()?>
        <?= $form->field($model, 'new_permissions')->widget(MultipleInput::className(), [
            'max' => 30,
            'min' => 0,
            'cloneButton' => true,
            'columns' => [
                [
                    'name'  => 'name',
                    'title' => 'Nomi',
                    'defaultValue' => "",
                ],
                [
                    'name'  => 'description',
                    'title' => 'Izoh',
                ],
            ]
        ])->label('Permissions');
        ?>

        <?=
        $form->field($model, 'category')->widget(Select2::classname(), [
            'data' => $model->getCategory($model->name),
            'options' => ['placeholder' => Yii::t('app','Select_Category'),'value' => $model->category],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
