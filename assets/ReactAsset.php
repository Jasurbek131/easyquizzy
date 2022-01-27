<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ReactAsset extends AssetBundle
{
    public static $reactFileName = 'index';
    public static $reactCssFileName = '';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/adminlte.css',
        'css/font-awesome.min.css',
//        'reactjs/dist/css/style-react.css',
//        'reactjs/dist/css/ReactToastify.css',
//        'reactjs/dist/css/react-datepicker.css',
    ];
    public function init()
    {
        parent::init();
        $reactFileName = self::$reactFileName;
        $reactCssFileName = self::$reactCssFileName;
        $this->js[] = "reactjs/dist/app/{$reactFileName}.bundle.js";
        if($reactCssFileName) {
            $this->css[] = "reactjs/dist/css/{$reactCssFileName}.css";
        }
    }
    public $js = [
        'js/adminlte.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
