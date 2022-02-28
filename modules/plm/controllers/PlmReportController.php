<?php


namespace app\modules\plm\controllers;


use yii\web\Controller;

class PlmReportController extends Controller
{
    public function actionDocument()
    {
       return  $this->render("document");
    }
}