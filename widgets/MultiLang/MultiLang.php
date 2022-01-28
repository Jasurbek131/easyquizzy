<?php
namespace app\widgets\MultiLang;

class MultiLang extends \yii\bootstrap\Widget
{
    public $cssClass;
    public function init(){}

    public function run() {

        return $this->render('view', [
            'cssClass' => $this->cssClass,
        ]);

    }
}