<?php

use app\models\BaseModel;
use app\modules\references\models\ReferencesProductGroup;
use app\modules\references\models\ReferencesProductGroupRelProduct;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card products-index">
    <?php if (P::can('products/create')): ?>
        <div class="card-header pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
                ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        </div>
    <?php endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'products_pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                    'format' => 'raw',
//                    'value' => function(ReferencesProductGroup $model) {
//                        return ReferencesProductGroupRelProduct::getProductsByGroup($model->id);
//                    },
                ],
                [
                    'attribute' => 'part_number',
                ],
//                [
//                    'attribute' => 'status_id',
//                    'format' => 'raw',
//                    'value' => function($model) {
//                        return BaseModel::getStatusList($model->status_id);
//                    },
//                    'filter' => BaseModel::getStatusList()
//                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print', 'style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' => P::can('products/view'),
                        'update' => function ($model) {
                            return P::can('products/update'); // && $model->status < $model::STATUS_SAVED;
                        },
                        'delete' => function ($model) {
                            return P::can('products/delete'); // && $model->status < $model::STATUS_SAVED;
                        }
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
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'products',
    'crud_name' => 'products',
    'modal_id' => 'products-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Products') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'products_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
