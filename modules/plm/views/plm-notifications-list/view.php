<?php

use app\modules\plm\models\BaseModel;
use app\modules\references\models\Reasons;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\plm\models\PlmNotificationsList */
/* @var $plmNotRelReasons app\modules\plm\models\PlmNotificationsListRelReason */
/* @var $plmNotRelDefects app\modules\plm\models\PlmNotificationRelDefect */

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
                                <th>№</th>
                                <th><?=Yii::t("app","Hr Department");?></th>
                                <th><?=Yii::t("app","Document Date");?></th>
                                <th><?=Yii::t("app","Shifts");?></th>
                                <th><?=Yii::t("app","Equipments");?></th>
                                <?php if(($model['token'] != 'REPAIRED') && ($model['token'] != 'SCRAPPED')):?>
                                    <th><?=Yii::t("app","Begin Time");?></th>
                                    <th><?=Yii::t("app","End Time");?></th>
                                <?php endif;?>
                                <th><?=Yii::t("app","Izoh");?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php $count = 1; echo $count;?></td>
                                <td><?=$model['department'];?></td>
                                <td><?=date('d.m.Y',strtotime($model['reg_date']));?></td>
                                <td><?=$model['shift'];?></td>
                                <td><?=$model['equipment'];?></td>
                                <?php if(($model['token'] != 'REPAIRED') && ($model['token'] != 'SCRAPPED')):?>
                                <td><?=date('d.m.Y H:i',strtotime($model['begin_time']));?></td>
                                <td><?=date('d.m.Y H:i',strtotime($model['end_time']));?></td>
                                <?php endif;?>
                                <td><?=$model['add_info'];?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if($plmNotRelReasons):?>
                        <table class="table table-bordered" style="background: #b6ffce;">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center"><?=Yii::t("app","Tasdiqlangan to'xtalishlar ro'yhati");?></th>
                                </tr>
                                <tr>
                                    <th>№</th>
                                    <th><?=Yii::t("app","To'xtalish nomi");?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $tr = 1; foreach ($plmNotRelReasons as $reason):?>
                                    <tr>
                                        <td class="text-center"><?=$tr;?></td>
                                        <td><?=$reason['reason_name'];?></td>
                                    </tr>
                                <?php $tr++; endforeach;?>
                            </tbody>
                        </table>
                    <?php else:?>
                    <?php endif;?>
                </div>
                <div class="col-md-12" style="float: right">
                    <?php if($plmNotRelDefects):
                        $title = ""; $head = "";
                        switch ($model['token']){
                            case 'REPAIRED': $title = "Repaired"; $head = "Repaired Title"; break;
                            case 'SCRAPPED': $title = "Scrapped"; $head = "Scrapped Title"; break;
                        }

                        ?>
                        <table class="table table-bordered" style="background: #b6ffce;">
                            <thead class="form-group">
                            <tr>
                                <th colspan="3" class="text-center"><?=Yii::t("app",$head);?></th>
                            </tr>
                            <tr>
                                <th class="text-center">№</th>
                                <th><?=Yii::t("app",$title);?></th>
                                <th class="text-center"><?=Yii::t("app","Count");?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $tr = 1; foreach ($plmNotRelDefects as $defect):?>
                                <tr>
                                    <td class="text-center"><?=$tr;?></td>
                                    <td><?=$defect['defect_name'];?></td>
                                    <td class="text-center"><?=$defect['defect_count'];?></td>
                                </tr>
                                <?php $tr++; endforeach;?>
                            </tbody>
                        </table>
                    <?php else:?>
                    <?php endif;?>
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
                            <legend style="text-align: center"><?=Yii::t("app","List of approved stops");?></legend>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                                <?php
                                    $reasons = Reasons::getCategoryList($model['category_id']);
                                ?>
                            <form action="" id="reasons-form" name="Reasons" method="POST">
                                <input type="hidden" id="plm_notification_list_id" value="<?=$model['id'];?>">
                                <fieldset>
                                    <div class="container">
                                        <div class="row checkbox_container">
                                            <?php if(!empty($reasons)):?>
                                                <?php foreach ($reasons as $key => $reason) : ?>
                                                    <div class="col-lg-6">
                                                        <div class="form-group small">
                                                            <label class="checkbox-transform">
                                                                <input type="checkbox" value="<?=$reason['id']?>"
                                                                       name="Reasons[<?php echo $key ?>]"
                                                                    <?php echo $model["name"][$key] !== null ? "checked" : "" ?>
                                                                       class="checkbox">
                                                                <span class="checkmark"></span>
                                                                <span class="p-10"><?php echo $reason['name'] ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else:?>
                                                <input type="hidden" id="empty_reason" value="0">
                                                <p class="form-group text-center" style="color: #0841cb"><?php echo Yii::t("app","Tasdiqlash uchun to'xtalishlar ro'yhati mavjud emas, Tasdiqlaysizmi ?");?></p>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </fieldset>
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
    <style>

        fieldset .form-group {
            margin-bottom: 0.2rem !important;
        }

        fieldset {
            margin: 0 0 30px 0;
            border: 2px solid #ccc;
            border-radius: 2px;
        }

        legend {
            background: #eee;
            padding: 4px 10px;
            color: #000;
            max-width: 100% !important;
            margin: 0 auto;
            display: inline;
        }

        .p-10 {
            padding-left: 15px !important;
        }
    </style>

<?php
$this->registerCss("
    .save_rejected{
        float:right;
        margin:10px 20px;
    }
");
$this->registerCssFile('/web/css/custom_checkbox.css');

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
      let empty_reason = $('#empty_reason').val();
      let form = $('#reasons-form');
      $.ajax({
            url: urlAccepted,
            type:"POST",
            data:{
                plm_notification_list_id:list_id,
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