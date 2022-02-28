<?php

use app\models\BaseModel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\RedirectUrlListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Redirect Url List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card redirect-url-list-index">
<!--    --><?php //if (Yii::$app->user->can('redirect-url-list/create')): ?>
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
<!--    --><?php //endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'redirect-url-list_pjax']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name_uz',
            'url:url',
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
                    'template' => '{update} {delete}',
                    'contentOptions' => ['class' => 'no-print text-center','style' => 'width:100px;'],
//                    'visibleButtons' => [
//                        'view' => Yii::$app->user->can('redirect-url-list/view'),
//                        'update' => function($model) {
//                            return Yii::$app->user->can('redirect-url-list/update'); // && $model->status < $model::STATUS_SAVED;
//                        },
//                        'delete' => function($model) {
//                            return Yii::$app->user->can('redirect-url-list/delete'); // && $model->status < $model::STATUS_SAVED;
//                        }
//                    ],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="fa fa-pencil-alt"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class'=> 'update-dialog btn btn-xs btn-success mr1',
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
    'model' => 'redirect-url-list',
    'crud_name' => 'redirect-url-list',
    'modal_id' => 'redirect-url-list-modal',
    'modal_header' => '<h5>'. Yii::t('app', 'Redirect Url List') . '</h5>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'redirect-url-list_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
