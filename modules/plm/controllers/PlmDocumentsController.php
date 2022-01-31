<?php

namespace app\modules\plm\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * PlmProcessingTimeController implements the CRUD actions for PlmProcessingTime model.
 */
class PlmDocumentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
