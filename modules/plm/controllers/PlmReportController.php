<?php


namespace app\modules\plm\controllers;


use yii\web\Controller;

class PlmReportController extends BaseController
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
    public function actionStop(): string
    {
        return  $this->render("stop");
    }

    public function actionPieChart(){
        return $this->render("pie_chart");
    }
}