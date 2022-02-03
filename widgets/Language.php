<?php
namespace app\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * @author Rasuljonov Jasurbek
 */
class Language extends Widget
{
    public $prefix = "name_";

    public function run()
    {
        $lang = in_array(Yii::$app->language, Yii::$app->params['language_list']) ? Yii::$app->language : "uz";
        echo $this->prefix.$lang;
    }
}
