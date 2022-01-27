<?php


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthItem|null */

/* @var $perms array|mixed */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app', 'Yangilash: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
<?php
$child = $model->getCategory($model->name);
$value = $model->getParenList($model->name, false);
$childs = $model->getParenList($model->name, true)
?>
<div class="box box-solid box-info">
    <div class="box-header">
        <div class="form-group small">
           <h4> <?= Yii::t('app', "Bollarini tanlang") ?></h4>
            <label class="checkbox-transform">
                <input type="checkbox" class="checkbox__input" id="select_all">
                <span class="checkbox__label"><?= Yii::t('app', "Hammasi belgilash") ?> </span>
            </label>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <?php foreach ($child as $key => $item) : ?>
                <?php if (empty($childs[$item])): ?>
                    <div class="col-md-3 col-sm-3 col-xs-6 col-lg-3 col-xl-2">
                        <div class="form-group small">
                            <label class="checkbox-transform">
                                <input type="checkbox"
                                       name="AuthItem[parents][<?= $key ?>]"
                                    <?= $value[$item] !== null ? 'checked' : '' ?>
                                       class="checkbox__input">
                                <span class="checkbox__label"><?= $item ?> </span>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success removedSubmitButton']) ?>
</div>
<?php ActiveForm::end(); ?>
<style>
    .all_selected {
        position: absolute;
        top: 10px;
        left: 122px;
    }

    .small {
        zoom: 0.9;
    }

    .checkbox__label:before {
        content: ' ';
        display: block;
        height: 2.5rem;
        width: 2.5rem;
        position: absolute;
        top: 0;
        left: 0;
        background: #ffdb00;
    }

    .checkbox__label:after {
        content: ' ';
        display: block;
        height: 2.5rem;
        width: 2.5rem;
        border: .35rem solid #ec1d25;
        position: absolute;
        top: 0;
        left: 0; /* background: #fff200; */
        transition: 100ms ease-in-out;
    }

    .checkbox__input:checked ~ .checkbox__label:after {
        border-top-style: none;
        border-right-style: none;
        -ms-transform: rotate(-45deg);
        transform: rotate(-45deg);
        height: 1.25rem;
        border-color: green
    }

    .checkbox-transform {
        position: relative;
        font-size: 13px;
        font-weight: 700;
        color: #333333;
        cursor: pointer;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .checkbox__label:after:hover, .checkbox__label:after:active {
        border-color: green
    }

    .checkbox__label {
        margin-right: 2.5rem;
        margin-left: 15px;
        line-height: .75;
        font-size: 11px;
    }

    .checkboxList {
        padding-top: 25px;
    }

    .checkboxList .form-group {
        float: left
    }
</style>
<?php
$js = <<<JS
    $('body').delegate('#select_all', 'click', function(e) { 
        let checkbox = $('.checkbox__input') 
        if($(this).is(':checked')){ 
            checkbox.attr('checked', true).trigger('change'); 
        }else{ 
            checkbox.attr('checked', false).trigger('change'); 
        }
    })
JS;
$this->registerJs($js)

?>
