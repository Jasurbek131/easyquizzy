<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\PlmDocumentReport;
use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use app\api\modules\v1\components\CorsCustom;

/**
 * Country Controller API
 */
class PlmDocumentReportController extends ActiveController
{
    public $modelClass = 'app\models\Users';

    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => CorsCustom::class
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [

                ],
            ],
            [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     *
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $get = $request->get();
        $type = $get["type"];

        $response = [
            "status" => true
        ];
        switch ($type){
            case "PLM_DOCUMENT_DATA":
                $response = PlmDocumentReport::documentData();
                break;
        }

        return $response;
    }
}
