<?php
/* @var $this yii\web\View */


use app\assets\ReactAsset;

ReactAsset::$reactFileName = 'index';
ReactAsset::$reactCssFileName = 'index';
ReactAsset::register($this);
?>
<?php
$this->title = Yii::t('app','welcome');
?>

    <?php
        echo Yii::t('app','Save');
    ?>
    <div class="card">
        <div class="card-body">
            <div id="root"></div>
            IndexBy
        </div>
    </div>
<?php
//$this->registerJsFile('js/snow.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
    $('#loading').hide();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);