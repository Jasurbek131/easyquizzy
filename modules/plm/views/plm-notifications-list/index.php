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
                if($model['status_id'] == BaseModel::STATUS_REJECTED) return ['style' => 'background:#ffcfcf'];
           },
           'columns' => [
               ['class' => 'yii\grid\SerialColumn'],
               [
                   'attribute' => 'department',
                   'label' => Yii::t("app","Hr Department"),
                   'value' => function($model){
                       return $model['department'];
                   }
               ],
               [
                   'attribute' => 'reg_date',
                   'label' => Yii::t("app","Reg Date"),
                   'value' => function($model){
                       return date('d.m.Y',strtotime($model['reg_date']));
                   }
               ],
               [
                   'attribute' => 'shift',
                   'label' => Yii::t("app","Shift Name"),
                   'value' => function($model){
                       return $model['shift'];
                   }
               ],
               [
                   'attribute' => 'equipment',
                   'label' => Yii::t("app","Equipments"),
                   'value' => function($model){
                       return $model['equipment'];
                   }
               ],
               [
                    'attribute' => 'types',
                    'label' => Yii::t("app","Production Type"),
                    'value' => function($model){
                       switch ($model['token']){
                           case 'WORKING_TIME': return Yii::t("app","Working Time");
                           case 'REPAIRED': return Yii::t("app","Repaired");
                           case 'SCRAPPED': return Yii::t("app","Scrapped");
                           case 'PLANNED': return Yii::t("app","Planned Stop");
                           case 'UNPLANNED': return Yii::t("app","Unplanned Stop");
                       }
                    }
               ],
               [
                   'attribute' => 'status_id',
                   'format' => 'raw',
                   'value' => function($model) {
                       return BaseModel::getStatusList($model['status_id']);
                   },
                   'headerOptions' => [
                           'style' => 'width:10%;'
                   ],
                   'filter' => BaseModel::getStatusList(),
               ],
               [
                   'class' => 'yii\grid\ActionColumn',
                   'template' => '{view}',
                   'contentOptions' => ['class' => 'no-print','style' => 'width:50px;'],
                   'visibleButtons' => [
                       'view' => Yii::$app->user->can('plm-notifications-list/view'),
                   ],
                   'buttons' => [
                       'view' => function ($url, $model) {
                           return Html::a('<span class="fa fa-eye"></span>', $url, [
                               'title' => Yii::t('app', 'View'),
                               'class'=>"btn btn-xs btn-primary",
                               'data-form-id' => $model['id'],
                           ]);
                       },
                   ],
               ],
           ],
       ]); ?>

       <?php Pjax::end(); ?>
   </div>

</div>
