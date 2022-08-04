<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="auth-items-view">
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <?php if (Yii::$app->user->can('auth-items/update')): ?>
                <?php if ($model->status != $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('auth-items/delete')): ?>
                <?php if ($model->status != $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->name], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?= Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
        </div>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
            'description:ntext',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                }
            ],
//            'category',
        ],
    ]) ?>
</div>
<div class="table-responsive">
    <h3 class="text-bold text-center"><?php echo ucfirst($model->name); ?></h3>
    <?php $i = 1;
    echo '<div class="container" style="border: 1px solid grey;border-radius: 10px; ">';
    foreach ($model::getPermissionChild($model->name) as $item => $key):?>
        <?php if ($i != 4): ?>
            <div class="col-md-4">
                        <span class="badge badge-success pt-10">
                            <?php echo $key['child']; ?>
                        </span>
            </div>
        <?php endif; ?>
        <?php if ($i == 4) {
            $i = 0;
        }
        $i++; ?>
    <?php endforeach;
    echo '</div>';
    ?>
</div>
<?php
$css = <<<CSS
    .badge{
        background-color: #2ca42c!important;
    }
    #w0  > tbody > tr:nth-child(4) > td{
        display: grid; 
        grid-template-columns: repeat(4,1fr);
        grid-gap: 3px;
    }
CSS;
$this->registerCss($css);
?>
