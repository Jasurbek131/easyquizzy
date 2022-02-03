<?php

use app\models\BaseModel;
use app\models\Users;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Permission\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card users-index">
<!--    --><?php //if (Yii::$app->user->can('users/create')): ?>
        <div class="card-header pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
                ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        </div>
<!--    --><?php //endif; ?>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'users_pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'hr_employee_id',
                    'value' => function(Users $model){
                        return !empty($model->hrEmployees) ?
                            $model->hrEmployees[0]->hrEmployee->lastname." ".
                            $model->hrEmployees[0]->hrEmployee->firstname." ".
                            $model->hrEmployees[0]->hrEmployee->fathername
                            : "";
                    }
                ],
                'username',
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
                    'contentOptions' => ['class' => 'no-print', 'style' => 'width:100px;'],
//                    'visibleButtons' => [
//                        'view' => Yii::$app->user->can('users/view'),
//                        'update' => function ($model) {
//                            return Yii::$app->user->can('users/update'); // && $model->status < $model::STATUS_SAVED;
//                        },
//                        'delete' => function ($model) {
//                            return Yii::$app->user->can('users/delete'); // && $model->status < $model::STATUS_SAVED;
//                        }
//                    ],
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
<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'users',
    'crud_name' => 'users',
    'modal_id' => 'users-modal',
    'modal_header' => '<h3>' . Yii::t('app', 'Users') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'users_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatdan ham o\'chirmoqchimisiz?')
]); ?>
