<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hr Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-departments-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-departments/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-departments/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
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
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'hr_organisation_id',
            ],
            [
                'attribute' => 'name_uz',
            ],
            [
                'attribute' => 'name_ru',
            ],
            [
                'attribute' => 'token',
            ],
            [
                'attribute' => 'status_id',
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
