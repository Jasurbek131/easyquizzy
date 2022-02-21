<?php

use app\modules\plm\models\BaseModel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\plm\models\PlmNotificationsListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Plm Notifications Lists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plm-notifications-list-index card">
   <div class="card-body">
       <?php Pjax::begin(); ?>
       <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

       <?= GridView::widget([
           'dataProvider' => $dataProvider,
           'filterRowOptions' => ['class' => 'filters no-print'],
           'filterModel' => $searchModel,
           'rowOptions' => function($model){
                if($model['status_id'] == BaseModel::STATUS_ACCEPTED) return ['style' => 'background:#92d7ff'];
           },
           'columns' => [
               ['class' => 'yii\grid\SerialColumn'],

//            'id',
               [
                   'attribute' => 'department',
                   'label' => Yii::t("app","Hr Department"),
                   'value' => function($model){
                       return $model['department'];
                   }
               ],
               [
                   'attribute' => 'shift',
                   'label' => Yii::t("app","Shifts"),
                   'value' => function($model){
                       return $model['shift'];
                   }
               ],
               [
                   'attribute' => 'product',
                   'label' => Yii::t("app","Products"),
                   'value' => function($model){
                       return $model['product'];
                   }
               ],
               [
                   'attribute' => 'begin_time',
                   'label' => Yii::t("app","Begin Time"),
                   'value' => function($model){
                       return $model['begin_time'];
                   },
                   'visible' => P::can('plm-notifications-list/working-time'),
               ],
               [
                   'attribute' => 'end_time',
                   'label' => Yii::t("app","End Time"),
                   'value' => function($model){
                       return $model['end_time'];
                   },
                   'visible' => P::can('plm-notifications-list/working-time'),
               ],
               [
                   'attribute' => 'defect_id',
                   'label' => Yii::t("app","Defects"),
                   'value' => function($model){
                       return $model['defect'];
                   },
                   'visible' => P::can('plm-notifications-list/repaired') || P::can('plm-notifications-list/invalid'),

               ],
               [
                   'attribute' => 'reason_id',
                   'label' => Yii::t("app","Reasons"),
                   'value' => function($model){
                       return $model['reason'];
                   },
                   'visible' => P::can('plm-notifications-list/planned') || P::can('plm-notifications-list/unplanned'),
               ],
               [
                   'attribute' => 'status_id',
                   'format' => 'raw',
                   'value' => function($model) {
                       return BaseModel::getStatusList($model['status_id']);
                   },
                   'filter' => BaseModel::getStatusList()
               ],
               //'created_by',
               //'created_at',
               //'updated_by',
               //'updated_at',
               [
                   'attribute' => 'add_info',
                   'label' => Yii::t("app","Add Info"),
                   'value' => function($model){
                       return $model['add_info'];
                   }
               ],
               //'plm_sector_list_id',

               [
                   'class' => 'yii\grid\ActionColumn',
                   'template' => '{view}',
                   'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                   'visibleButtons' => [
                       'view' => Yii::$app->user->can('plm-notifications-list/view'),
                       /*'update' => function($model) {
                           return Yii::$app->user->can('plm-notifications-list/update') && $model->status_id < $model::STATUS_SAVED;
                       },
                       'delete' => function($model) {
                           return Yii::$app->user->can('plm-notifications-list/delete') && $model->status_id < $model::STATUS_SAVED;
                       }*/
                   ],
                   'buttons' => [
                       /*'update' => function ($url, $model) {
                           return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                               'title' => Yii::t('app', 'Update'),
                               'class'=>"btn btn-xs btn-success"
                           ]);
                       },*/
                       'view' => function ($url, $model) {
                           return Html::a('<span class="fa fa-eye"></span>', $url, [
                               'title' => Yii::t('app', 'View'),
                               'class'=>"btn btn-xs btn-primary",
                               'data-form-id' => $model['id'],
                           ]);
                       },
                       /*'delete' => function ($url, $model) {
                           return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                               'title' => Yii::t('app', 'Delete'),
                               'class' => "btn btn-xs btn-danger",
                               'data' => [
                                   'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                   'method' => 'post',
                               ],
                           ]);
                       },*/

                   ],
               ],
           ],
       ]); ?>

       <?php Pjax::end(); ?>
   </div>

</div>
