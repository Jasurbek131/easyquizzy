<?php
/* @var $this yii\web\View */


use yii\helpers\Html; ?>

<?php
$this->title = Yii::t('app','welcome');
?>
    <div class="card">
        <div class="card-body">
            Index
        </div>
    </div>
<?php
//$this->registerJsFile('js/snow.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
    $('#loading').hide();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);