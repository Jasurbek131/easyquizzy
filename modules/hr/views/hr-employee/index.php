<?php

use app\modules\hr\models\HrEmployee;
use app\widgets\Language;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', "Hr Employee");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card hr-employee-index">
    <!--    --><?php //if (Yii::$app->user->can('hr-employee/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
            ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
    <!--    --><?php //endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'hr-employee_pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'firstname',
                'lastname',
                'fathername',
                'phone_number',
                'email:email',
                [
                    'attribute' => 'hr_department_id',
                    'label' => Yii::t("app", "Hr Department"),
                    'value' => function (HrEmployee $model) {
                        return $model->hrEmployeeActivePosition ? ($model->hrEmployeeActivePosition->hrDepartments->name ?? "") : "";
                    }
                ],
                [
                    'attribute' => 'hr_position_id',
                    'label' => Yii::t("app", "Hr Position"),
                    'value' => function (HrEmployee $model) {
                        return $model->hrEmployeeActivePosition ? ($model->hrEmployeeActivePosition->hrPositions[Language::widget()] ?? "") : "";
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print text-center', 'style' => 'width:100px;'],
                    'visibleButtons' => [
//                        'view' => Yii::$app->user->can('hr-employee/view'),
//                        'update' => function($model) {
//                            return Yii::$app->user->can('hr-employee/update'); // && $model->status < $model::STATUS_SAVED;
//                        },
//                        'delete' => function($model) {
//                            return Yii::$app->user->can('hr-employee/delete'); // && $model->status < $model::STATUS_SAVED;
//                        }
                    ],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="fa fa-pencil-alt"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class' => 'update-dialog btn btn-xs btn-success mr1',
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'view' => function ($url, $model) {
                            return Html::a('<span class="fa fa-eye"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class' => 'btn btn-xs btn-primary view-dialog mr1',
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="fa fa-trash-alt"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-xs btn-danger delete-dialog',
                                'data-form-id' => $model->id,
                            ]);
                        },
                    ],
                ],
            ],
        ]) ?>

        <?php Pjax::end(); ?>
    </div>
</div>
<?php echo \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'hr-employee',
    'crud_name' => 'hr-employee',
    'modal_id' => 'hr-employee-modal',
    'modal_header' => '<h5>' . Yii::t('app', 'Hr Employee') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'hr-employee_pjax',
    'confirm_message' => Yii::t('app', 'Are you sure you want to delete this item?')
]);
$this->registerCss('
    .modal-lg{
        max-width:80%;
    }
');
?>

<?php
$this->registerJsVar("getDepertmentUrl", Url::to(["/hr/hr-departments/get-departments"]));
$js = <<<JS
    function hrOrganisationChange() {
        $("body").delegate("#hr_organisation_id", 'change', function(e) {
            let parent_id = $(this).val(); // hr_organisation_id
            $("#hr_department_id").html("");
            
            if (parent_id){
                $.ajax({
                    url: getDepertmentUrl + "?parent_id=" + parent_id,
                    success: function(data){ 
                        if (data.status){
                           data.departments.forEach(function (val, key) {
                                let newOption = new Option(val.label, val.value);
                                $("#hr_department_id").append(newOption).trigger('change');
                            });
                        }
                    },
                });
            }
        });
    }
    hrOrganisationChange();

JS;
$this->registerJs($js, View::POS_READY);
?>
