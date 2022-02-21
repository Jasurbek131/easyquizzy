<?php

use app\modules\plm\models\BaseModel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
               'begin_time',
               'end_time',
//            'defect_id',
               /* [
                    'attribute' => 'defect_type_id',
                    'label' => Yii::t("app","Defect Type"),
                    'value' => function($model){
                        return $model['reason'];
                    }
                ],*/
               [
                   'attribute' => 'defect_id',
                   'label' => Yii::t("app","Defects"),
                   'value' => function($model){
                       return $model['defect'];
                   }
               ],
               [
                   'attribute' => 'reason_id',
                   'label' => Yii::t("app","Reasons"),
                   'value' => function($model){
                       return $model['reason'];
                   }
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
               'add_info:ntext',
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
