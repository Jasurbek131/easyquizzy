<?php

use app\models\BaseModel;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\Products;
use app\modules\references\models\TimeTypesList;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\ProductLifecycleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Product Lifecycles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card product-lifecycle-index">
<!--    --><?php //if (Yii::$app->user->can('product-lifecycle/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
<!--    --><?php //endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'product-lifecycle_pjax']); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product_id',
                'format' => 'raw',
                'value' => function($model) {
                    return Products::getList($model->product_id);
                },
                'filter' => Products::getList()
            ],
            [
                'attribute' => 'equipment_group_id',
                'format' => 'raw',
                'value' => function($model) {
                    return EquipmentGroup::getList($model->equipment_group_id);
                },
                'filter' => EquipmentGroup::getList()
            ],
            'lifecycle',
            [
                'attribute' => 'time_type_id',
                'format' => 'raw',
                'value' => function($model) {
                    return TimeTypesList::getList($model->time_type_id);
                },
                'filter' => TimeTypesList::getList()
            ],
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
//                        'view' => Yii::$app->user->can('product-lifecycle/view'),
//                        'update' => function($model) {
//                            return Yii::$app->user->can('product-lifecycle/update'); // && $model->status < $model::STATUS_SAVED;
//                        },
//                        'delete' => function($model) {
//                            return Yii::$app->user->can('product-lifecycle/delete'); // && $model->status < $model::STATUS_SAVED;
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
    'model' => 'product-lifecycle',
    'crud_name' => 'product-lifecycle',
    'modal_id' => 'product-lifecycle-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Product Lifecycle') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'product-lifecycle_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
