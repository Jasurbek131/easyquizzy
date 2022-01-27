<?php
/* @var $this yii\web\View */


use yii\helpers\Html; ?>

<?php
$this->title = Yii::t('app','welcome');
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="image text-center">Bu yerda rasm bo'ladi</div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="image text-center">Bu yerda ham rasm bo'ladi</div>
            <br>
            <h3 class="text-orange"><?= mb_strtoupper(\Yii::t('app', 'Text about this system'))?></h3>
        </div>
    </div>
<?php
//$this->registerJsFile('js/snow.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
    $('#loading').hide();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);