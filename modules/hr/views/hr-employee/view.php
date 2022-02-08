<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $hrEmployeeRel app\modules\hr\models\HrEmployeeRelPosition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hr Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-employee-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-employee/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-employee/delete')): ?>
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
                'attribute' => 'firstname',
            ],
            [
                'attribute' => 'lastname',
            ],
            [
                'attribute' => 'fathername',
            ],
            [
                'attribute' => 'phone_number',
            ],
            [
                'attribute' => 'email',
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
        ],
    ]) ?>
    <div class="row">
        <div class="col-md-12">
            <h5 class="text-center"><?=Yii::t("app","Hodimga shu vaqtgacha biritirgan lavozimlar");?></h5>
            <?php if($hrEmployeeRel):?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-bold"><?=Yii::t("app","№");?></td>
                            <td class="text-bold"><?=Yii::t("app","Hr Departments");?></td>
                            <td class="text-bold"><?=Yii::t("app","Hr Positions");?></td>
                            <td class="text-bold"><?=Yii::t("app","Begin Date");?></td>
                            <td class="text-bold"><?=Yii::t("app","End Date");?></td>
                            <td class="text-bold"><?=Yii::t("app","Status");?></td>
                        </tr>
                    </thead>
                    <tbody>
                            <?php $i = 1; foreach ($hrEmployeeRel as $item):?>
                            <?php if($item['status'] == \app\models\BaseModel::STATUS_ACTIVE):
                                    $class = "background:#B9FEA4";
                            ?>
                            <?php else:
                                    $class = "background:#FFB4A2";
                            ?>
                            <?php endif;?>
                            <tr style="<?=$class?>">
                                <td><?=$i?></td>
                                <td><?=$item['department_name']?></td>
                                <td><?=$item['position_name']?></td>
                                <td><?=($item['begin_date']) ? date('d.m.Y',strtotime($item['begin_date'])) : ""?></td>
                                <td><?=($item['end_date']) ? date('d.m.Y',strtotime($item['end_date'])) : ""?></td>
                                <td><?=$item['status_name']?></td>
                            </tr>
                            <?php $i++; endforeach;?>
                    </tbody>
                </table>
            <?php endif;?>
        </div>
    </div>
</div>
