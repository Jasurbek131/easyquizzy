<?php

use app\components\TabularInput\CustomMultipleInput;
use app\modules\admin\models\AuthItem;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $perms */
/* @var $permission yii\web\View */
/* @var $model app\modules\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if(!$permission){?>
<div class="auth-item-form">
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?php echo $form->field($model, 'name_for_user')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if ($model->type == 1): ?>
        <?php echo $form->field($model, 'role_type')->dropDownList(AuthItem::getRoleType()) ?>
    <?php endif;?>

    <?php echo $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?php if ($model->type == 2): ?>
        <?php echo
        $form->field($model, 'category')->widget(Select2::class, [
            'data' => $model->getCategory($model->name),
            'options' => ['placeholder' => Yii::t('app','Select_Category'),'value' => $model->category],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    <?php endif;?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php if ($model->type !== 2): ?>
        <h4> <?php echo $form->field($model, "perms[0]")->checkbox(['label' => Yii::t('app','Permissions'), 'class'=>'checkbox-success','id' => 'check-permissions'])->label(false) ?></h4>
        <div class="col-md-12" id="permissions-content" style="display: none;">
            <?php foreach ($perms as $key => $allperm):?>
                <fieldset class="col-md-12" style="margin-bottom: -20px">
                    <legend><?php echo $key?>
                        <label>
                            <input type="checkbox" class="checkbox-check" value="1" data-checked="Hammasini tanlash" data-unchecked="Hammasini bekor qilish"> <span class="label_checkbox">Hammasini tanlash</span>
                        </label>
                    </legend>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php foreach ($allperm as $key => $perm): ?>
                                <div class="col-md-4">
                                    <?php echo $form->field($model, "perms[{$perm}]")->checkbox(['checked' => $model->checkPermitionChecked($perm), 'label' => $perm])->label(false) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </fieldset>
            <?php endforeach;?>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>
</div>
<?php }else{?>
    <div class="auth-item-form">
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

        <?php echo $form->field($model,'name')->textInput()?>

        <?php echo $form->field($model, 'new_permissions')->widget(CustomMultipleInput::class, [
            'max' => 30,
            'min' => 0,
            'cloneButton' => true,
            'addButtonOptions' => [
                'class' => 'btn btn-xs btn-success'
            ],
            'removeButtonOptions' => [
                'class' => 'btn btn-xs btn-danger'
            ],
            'cloneButtonOptions' => [
                'class' => 'btn btn-xs btn-info'
            ],
            'iconSource' => CustomMultipleInput::ICONS_SOURCE_FONTAWESOME,
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
        <?php echo
        $form->field($model, 'category')->widget(Select2::class, [
            'data' => $model->getCategory($model->name),
            'options' => ['placeholder' => Yii::t('app','Select_Category'),'value' => $model->category],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        <div class="form-group">
            <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php }?>