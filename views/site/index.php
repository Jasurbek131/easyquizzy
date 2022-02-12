<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<?php
$this->title = Yii::t('app','welcome');
?>
    <div class="card" style="background: #F7F7F7;">
       <div class="card-body">
               <div class="image text-center"><?= Html::img('/img/index-logo2.png', ['style'=>'width:auto;height:400px;'])?></div>
           <br>
           <h4 class="text-orange text-center"><?= mb_strtoupper(\Yii::t('app', 'Text about this system'))?></h4>
       </div>
    </div>
<?php
$js = <<< JS
    $('#loading').hide();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);