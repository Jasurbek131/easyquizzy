<?php

use app\modules\plm\models\PlmSectorList;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\plm\models\PlmSectorRelHrDepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Plm Sector Rel Hr Departments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card plm-setting-accepted-sector-rel-hr-department-index">
    <?php if (Yii::$app->user->can('plm-sector-rel-hr-department/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
    <?php endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'plm-setting-accepted-sector-rel-hr-department_pjax']); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'hr_department_id',
                'value' => function($model){
                    $info = $model->hrDepartments->name;
                    return $info;
                }
            ],
            [
                'attribute' => 'plm_sector_list_id',
                'value' => function($model){
                    $info = $model->plmSectorList->name_uz;
                    return $info;
                },
                'filter' => PlmSectorList::getList(),
            ],
            [
                'attribute' => 'status_id',
                'value' => function($model){
                    $info = $model::getStatusList($model->status_id);
                    return $info;
                },
                'format' => 'html'
            ],
//            'status_id',
//            'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print text-center','style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('plm-sector-rel-hr-department/view'),
                        'update' => function($model) {
                            return Yii::$app->user->can('plm-sector-rel-hr-department/update'); // && $model->status < $model::STATUS_SAVED;
                        },
                        'delete' => function($model) {
                            return Yii::$app->user->can('plm-sector-rel-hr-department/delete'); // && $model->status < $model::STATUS_SAVED;
                        }
                    ],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="fa fa-pencil-alt"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class'=> 'update-dialog btn btn-xs btn-success mr1',
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'view' => function ($url, $model) {
                            return Html::a('<span class="fa fa-eye"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class'=> 'btn btn-xs btn-primary view-dialog mr1',
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
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'plm-sector-rel-hr-department',
    'crud_name' => 'plm-sector-rel-hr-department',
    'modal_id' => 'plm-sector-rel-hr-department-modal',
    'modal_header' => '<h5>'. Yii::t('app', 'Plm Sector Rel Hr Department') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'plm-setting-accepted-sector-rel-hr-department_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
