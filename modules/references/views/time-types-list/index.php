<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\references\models\BaseModel;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\TimeTypesListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Time Types Lists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card time-types-list-index">
<!--    --><?php //if (Yii::$app->user->can('time-types-list/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
<!--    --><?php //endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'time-types-list_pjax']); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'attribute' => 'status_id',
                    'format' => 'raw',
                    'value' => function($model) {
                        return BaseModel::getStatusList($model->status_id);
                    },
                    'filter' => BaseModel::getStatusList()
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                    'visibleButtons' => [
//                        'view' => Yii::$app->user->can('time-types-list/view'),
//                        'update' => function($model) {
//                            return Yii::$app->user->can('time-types-list/update'); // && $model->status < $model::STATUS_SAVED;
//                        },
//                        'delete' => function($model) {
//                            return Yii::$app->user->can('time-types-list/delete'); // && $model->status < $model::STATUS_SAVED;
//                        }
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
        ]); ?>
    
        <?php Pjax::end(); ?>
    </div>
</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'time-types-list',
    'crud_name' => 'time-types-list',
    'modal_id' => 'time-types-list-modal',
    'modal_header' => '<h5>'. Yii::t('app', 'Time Types List') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'time-types-list_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
