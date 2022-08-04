<?php

use app\modules\references\models\Categories;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Stops group');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card categories-index">
    <?php if (P::can('categories/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
    <?php endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'categories_pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name_uz',
            'name_ru',
            [
                'attribute' => 'type',
                'value' => function(Categories $model){
                    $type = $model::getCategoryTypeList($model->type);
                    return is_string($type) ? $type : "";
                },
                'filter' => Categories::getCategoryTypeList()
            ],
//            [
//                'attribute' => 'status_id',
//                'value' => function(Categories $model){
//                    return $model::getStatusList($model->status_id);
//                },
//                'format' => 'html',
//                'filter' => $searchModel->getStatusList()
//            ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print text-center','style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' => P::can('categories/view'),
                        'update' => function($model) {
                            return P::can('categories/update'); // && $model->status < $model::STATUS_SAVED;
                        },
                        'delete' => function($model) {
                            return P::can('categories/delete'); // && $model->status < $model::STATUS_SAVED;
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
    'model' => 'categories',
    'crud_name' => 'categories',
    'modal_id' => 'categories-modal',
    'modal_header' => '<h5>'. Yii::t('app', 'Categories') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'categories_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
