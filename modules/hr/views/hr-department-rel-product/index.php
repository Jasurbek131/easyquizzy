<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrDepartmentRelProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hr Department Rel Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card hr-department-rel-product-index">
    <?php if (Yii::$app->user->can('hr-department-rel-product/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
    <?php endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'hr-department-rel-product_pjax']); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'hr_department_id',
            'product_id',
            'status_id',
            'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print text-center','style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('hr-department-rel-product/view'),
                        'update' => function($model) {
                            return Yii::$app->user->can('hr-department-rel-product/update'); // && $model->status < $model::STATUS_SAVED;
                        },
                        'delete' => function($model) {
                            return Yii::$app->user->can('hr-department-rel-product/delete'); // && $model->status < $model::STATUS_SAVED;
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
    'model' => 'hr-department-rel-product',
    'crud_name' => 'hr-department-rel-product',
    'modal_id' => 'hr-department-rel-product-modal',
    'modal_header' => '<h5>'. Yii::t('app', 'Hr Department Rel Product') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'hr-department-rel-product_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
