<?php

use app\modules\plm\models\BaseModel;
use app\modules\references\models\Reasons;
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
            <div class="row">
                <?php if(!Yii::$app->request->isAjax){?>
                    <div class="pull-right" style="margin-bottom: 15px;float: right">
                        <?php  if ($model['status_id'] < BaseModel::STATUS_SAVED): ?>
                            <?= Html::button(Yii::t('app', 'Accepted'), [
                                'class' => 'btn btn-sm btn-primary accepted',
                            ]) ?>
                        <?php endif; ?>
                        <?php  if ($model['status_id'] < BaseModel::STATUS_SAVED): ?>
                            <?= Html::button(Yii::t('app', 'Rejected'), [
                                'class' => 'btn btn-sm btn-danger rejected',
                            ]) ?>
                        <?php endif; ?>
                        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-sm btn-info']) ?>
                    </div>
                <?php }?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="text-bold">
                            <tr>
                                <td>№</td>
                                <td><?=Yii::t("app","Hr Department");?></td>
                                <td><?=Yii::t("app","Hujjat sanasi");?></td>
                                <td><?=Yii::t("app","Smenasi");?></td>
                                <td><?=Yii::t("app","Ish vaqti boshlanishi");?></td>
                                <td><?=Yii::t("app","Ish vaqti tugashi");?></td>
                                <td><?=Yii::t("app","Tamirlangan");?></td>
                                <td><?=Yii::t("app","Yaroqsiz");?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php $count = 1; echo $count;?></td>
                                <td><?=$model['department'];?></td>
                                <td><?=date('d.m.Y',strtotime($model['reg_date']));?></td>
                                <td><?=$model['shift'];?></td>
                                <td><?=date('d.m.Y',strtotime($model['begin_time']));?></td>
                                <td><?=date('d.m.Y',strtotime($model['end_time']));?></td>
                                <td><?=$model['defect'];?></td>
                                <td><?=$model['defect_count'];?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
            <div class="modal md"  id="modal_accepted" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="text-center"><?php echo Yii::t('app','Tasdiqlanadigan to\'xtalishlar ro\'yhati!')?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                                <?php
                                    $reasons = Reasons::getCategoryList($model['category_id']);
                                ?>
                            <form action="" id="reasons-form" name="Reasons" method="POST">
                                <input type="hidden" id="plm_notification_list_id" value="<?=$model['id'];?>">
                                <div class="row">
                                    <?php if(!empty($reasons)):?>
                                        <?php foreach ($reasons as $key => $reason):?>
                                            <div class="col-md-3">
                                                <label for=""><?=$reason['name'];?></label>
                                                <input type="checkbox"  name="Reasons[<?=$key?>]" value="<?=$reason['id'];?>" data-id="<?=$reason['id']?>">
                                            </div>
                                        <?php endforeach;?>
                                    <?php else:?>
                                        <h5 class="text-center"><?=Yii::t("app","Sabablar mavjud emas! Tasdiqlaysizmi ?");?></h5>
                                        <input type="hidden" id="empty_reason" value="1">
                                    <?php endif;?>
                                </div>
                            </form>
                                <br>
                                <div class="col-md-12">
                                    <?= Html::submitButton(Yii::t('app', 'Accepted'), ['class' => 'btn btn-sm btn-success save_accepted']) ?>
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
$url_accepted = Url::to(['ajax-accepted']);
$this->registerJsVar('urlRejected',$url_rejected);
$this->registerJsVar('urlAccepted',$url_accepted);
$js = <<< JS
    $('body').delegate('.rejected','click', function(e){
       $('#modal_rejected').modal('show');
   });
     $('body').delegate('.accepted','click', function(e){
           $('#modal_accepted').modal('show');
       });
   $('body').delegate('.save_accepted','click',function() {
      let list_id = $('#plm_notification_list_id').val();
      let empty_reason = $('#empty-reason').val();
      let form = $('#reasons-form');
      $.ajax({
            url: urlAccepted,
            type:"POST",
            data:{
                list_id:list_id,
                empty_reason:empty_reason,
                form:form.serializeArray(),
            },
            success:function(response) {
                if(response.status){
                    call_pnotify('success',response.message);                     
                    window.location.reload();
                }else{
                    call_pnotify('fail',response.message);
                }
            }
      });
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