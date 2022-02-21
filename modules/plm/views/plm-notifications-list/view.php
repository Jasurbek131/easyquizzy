<?php

use app\modules\plm\models\BaseModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmNotificationsList */

//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Notifications Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plm-notifications-list-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;float: right">
            <?php  if ($model['status_id'] < BaseModel::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Accepted'), ['accepted', 'id' => $model['id']], ['class' => 'btn btn-sm btn-primary']) ?>
            <?php endif; ?>
            <?php  if ($model['status_id'] < BaseModel::STATUS_SAVED): ?>
                <?= Html::button(Yii::t('app', 'Rejected'), [
                    'class' => 'btn btn-sm btn-danger rejected',
                ]) ?>
            <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-sm btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           /* [
                'attribute' => 'id',
            ],*/
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
            /*[
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => function($model) {
                    return BaseModel::getStatusList($model['status_id']);
                },
            ],*/
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    $username = \app\models\Users::findOne($model['created_by'])['user_fio'];
                    return isset($username)?$username:$model['created_by'];
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model['created_at']<(60*60*24))?Yii::$app->formatter->format(date($model['created_at']), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    $username = \app\models\Users::findOne($model['updated_by'])['user_fio'];
                    return isset($username)?$username:$model['updated_by'];
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model['updated_at']<(60*60*24))?Yii::$app->formatter->format(date($model['updated_at']), 'relativeTime'):date('d.m.Y H:i',$model['updated_at']);
                }
            ],
            [
                'attribute' => 'add_info',
            ],
            /*[
                'attribute' => 'plm_sector_list_id',
            ],*/
        ],
    ]) ?>
    <div class="modal md"  id="modal_rejected" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-center"><?php echo Yii::t('app','Rad etish sababini kiriting!')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="plm_notification_list_id" value="<?=$model['id'];?>">
                            <textarea name="PlmNotificationList" id="message" cols="66" rows="3"></textarea>
                        </div>
                        <br>
                        <div class="col-md-12">
                           <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-sm btn-success save_rejected']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerCss("
    .save_rejected{
        float:right;
        margin:10px 20px;
    }
");

$url_rejected = Url::to(['ajax-rejected']);
$this->registerJsVar('urlRejected',$url_rejected);
$js = <<< JS
    $('body').delegate('.rejected','click', function(e){
       $('#modal_rejected').modal('show');
   });
   $('body').delegate('.save_rejected','click',function() {
      let list_id = $('#plm_notification_list_id').val();
      let message = $('#message').val();
      $.ajax({
            url: urlRejected,
            data:{
                list_id:list_id,
                message: message,
            },
            type:"POST",
            success:function(response) {
                if(response.status){
                    call_pnotify('success',response.message);
                }else{
                    call_pnotify('fail',response.message);
                }
            }
      });
   });
JS;

$this->registerJs($js,\yii\web\View::POS_READY);
?>