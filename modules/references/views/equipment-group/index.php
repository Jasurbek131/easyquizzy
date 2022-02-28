<?php

use app\models\BaseModel;
use app\modules\references\models\EquipmentGroupRelationEquipment;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\EquipmentGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Equipment Groups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card equipment-group-index">
    <div class="card-header pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['new-create'],
            ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
    </div>
    <div class="card-body">
<!--        --><?php //Pjax::begin(['id' => 'equipment-group_pjax']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'equipments',
                'label' => Yii::t("app","Equipments"),
                'format' => 'raw',
                'value' => function($model) {
                    return EquipmentGroupRelationEquipment::getGroupEquipments($model->id);
                }
            ],
            [
                'attribute' => 'value',
                'label' => Yii::t("app","Value"),
                'value' => function($model) {
                    return $model->value ?? "";
                },
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
                'template' => '{view} {new-update}', /* {delete}*/
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
//                    'view' => Yii::$app->user->can('equipment_group/view'),
//                    'update' => function($model) {
//                        return Yii::$app->user->can('equipment_group/update'); // && $model->status < $model::STATUS_SAVED;
//                    },
//                    'delete' => function($model) {
//                        return Yii::$app->user->can('equipment_group/delete'); // && $model->status < $model::STATUS_SAVED;
//                    }
                ],
                'buttons' => [
                    'new-update' => function ($url, $model) {
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
//                    'delete' => function ($url, $model) {
//                        return Html::a('<span class="fa fa-trash-alt"></span>', $url, [
//                            'title' => Yii::t('app', 'Delete'),
//                            'class' => 'btn btn-xs btn-danger delete-dialog',
//                            'data-form-id' => $model->id,
//                        ]);
//                    },
                ],
            ],
        ],
    ]); ?>

<!--        --><?php //Pjax::end(); ?>
    </div>
</div>

