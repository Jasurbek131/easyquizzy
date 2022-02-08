<?php

namespace app\assets;

class SweetAlertAsset extends \yii\web\AssetBundle
{
    public $basePath='@web';
    public $css = [
        '/css/sweetalert/sweetalert.css',
    ];
    public $js = [
        '/js/sweetalert/sweetalert.min.js'
    ];
}
