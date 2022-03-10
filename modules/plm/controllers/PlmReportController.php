<?php


namespace app\modules\plm\controllers;


use yii\web\Controller;

class PlmReportController extends Controller
{
    /**
     * @return string
     */
    public function actionDocument(): string
    {
       return  $this->render("document");
    }

    /**
     * @return string
     */
    public function actionDefect(): string
    {
        return  $this->render("defect");
    }
}