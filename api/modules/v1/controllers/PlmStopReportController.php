<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\PlmStopReport;
use app\modules\plm\models\PlmStops;
use app\modules\references\models\Categories;
use app\modules\references\models\Reasons;
use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use app\api\modules\v1\components\CorsCustom;

/**
 * Country Controller API
 */
class PlmStopReportController extends ActiveController
{
    public $modelClass = 'app\models\Users';

    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function actions(): array
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
    public function behaviors(): array
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
     * @return bool[]
     */
    public function actionIndex(): array
    {
        $request = Yii::$app->request;
        $get = $request->get();
        $post = $request->post();
        $type = $get["type"];

        $response = [
            "status" => true
        ];
        switch ($type){
            case "PLM_STOP_DATA":
                if ($post['is_search'] == false){
                    $response["stop_list"] = Reasons::getList(false, Categories::TOKEN_UNPLANNED);
                    $response["category_list"] = Categories::getList(false,[
                        'token' => Categories::TOKEN_UNPLANNED
                    ]);
                }
                $dataProvider = PlmStopReport::getStopData($post);
                $response['items'] = $dataProvider->getModels();
                $response['pagination'] = $dataProvider->getPagination();
                $response['status'] = true;
                break;
        }

        return $response;
    }
}
