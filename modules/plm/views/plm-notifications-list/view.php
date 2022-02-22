<?php

use app\modules\plm\models\BaseModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmNotificationsList */

//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plm Notifications Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
    <div class="card-body">
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
                    [
                        'attribute' => 'equipment',
                        'label' => Yii::t("app","Equipments"),
                        'value' => function($model){
                            return $model['equipment'];
                        }
                    ],
                    [
                        'attribute' => 'begin_time',
                        'label' => Yii::t("app","Begin Time"),
                        'value' => function($model){
                            return $model['begin_time'];
                        },
                        'visible' => P::can('plm-notifications-list/working-time') || P::can('plm-notifications-list/planned') || P::can('plm-notifications-list/unplanned'),
                    ],
                    [
                        'attribute' => 'end_time',
                        'label' => Yii::t("app","End Time"),
                        'value' => function($model){
                            return $model['end_time'];
                        },
                        'visible' => P::can('plm-notifications-list/working-time') || P::can('plm-notifications-list/planned') || P::can('plm-notifications-list/unplanned'),
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
                        'attribute' => 'defect_count',
                        'label' => Yii::t("app","Defects Count"),
                        'value' => function($model){
                            return ($model['defect_count']) ? ($model['defect_count'])." ta" : " ";
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
                        'label' => Yii::t("app","Bypass Time"),
                        'value' => function($model){
                            return $model['by_pass'];
                        },
                        'visible' => P::can('plm-notifications-list/unplanned'),
                    ],
                    [
                        'label' => Yii::t("app","Add Info"),
                        'value' => function($model){
                            return $model['add_info'];
                        },
                        'visible' => P::can('plm-notifications-list/planned') || P::can('plm-notifications-list/unplanned'),
                    ],

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
                message: message
            },
            type:"POST",
            success:function(response) {
                console.log(response);
                if(response.status){
                    call_pnotify('success',response.message);                     
                    window.location.reload();
                }else{
                    call_pnotify('fail',response.message);
                }
            }
      });
   });
function call_pnotify(status,text) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 4000;
            PNotify.alert({text:text,type:'success'});
            break;
        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 4000;
            PNotify.alert({text:text,type:'error'});
            break;
    }
}

JS;

$this->registerJs($js,\yii\web\View::POS_READY);
?>