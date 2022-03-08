<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmSectorRelHrDepartment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Sector Rel Hr Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plm-setting-accepted-sector-rel-hr-department-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('plm-sector-rel-hr-department/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('plm-sector-rel-hr-department/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           /* [
                'attribute' => 'id',
            ],*/
            [
                'attribute' => 'hr_department_id',
                'value' => function($model){
                    $info = $model->hrDepartments->name;
                    return $info;
                }
            ],
            [
                'attribute' => 'plm_sector_list_id',
                'value' => function($model){
                    $info = $model->plmSectorList->name_uz;
                    return $info;
                }
            ],
            [
                'attribute' => 'status_id',
                'value' => function($model){
                    $info = $model::getStatusList($model->status_id);
                    return $info;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    if ($model->created_by) {
                        $username = \app\models\Users::findOne($model->created_by)['username'];
                        return $username ?? $model->created_by;
                    }
                    return false;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    if ($model->updated_by) {
                        $username = \app\models\Users::findOne($model->updated_by)['username'];
                        return $username ?? $model->updated_by;
                    }
                    return false;
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
