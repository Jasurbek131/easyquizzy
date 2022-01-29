<?php

use app\models\BaseModel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrPositionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hr Positions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card hr-positions-index">
<!--    --><?php //if (Yii::$app->user->can('hr-positions/create')): ?>
    <p class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </p>
<!--    --><?php //endif; ?>

    <?php Pjax::begin(['id' => 'hr-positions_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name_uz',
            'name_ru',
            [
                'attribute' => 'status_id',
                'value' => function($model) {
                    return $model['status_id'] ? BaseModel::getStatusList($model['status_id']) : "";
                },
                'filter' => BaseModel::getStatusList(),
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
//                'visibleButtons' => [
//                    'view' => Yii::$app->user->can('hr-positions/view'),
//                    'update' => function($model) {
//                        return Yii::$app->user->can('hr-positions/update'); // && $model->status < $model::STATUS_SAVED;
//                    },
//                    'delete' => function($model) {
//                        return Yii::$app->user->can('hr-positions/delete'); // && $model->status < $model::STATUS_SAVED;
//                    }
//                ],
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
                            'class'=> 'btn btn-xs btn-default view-dialog mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="fa fa-trash"></span>', $url, [
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
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'hr-positions',
    'crud_name' => 'hr-positions',
    'modal_id' => 'hr-positions-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Hr Positions') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'hr-positions_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
