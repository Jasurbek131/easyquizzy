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
        $lang = Yii::$app->language ?? "uz";
        echo $this->prefix.$lang;
    }
}
