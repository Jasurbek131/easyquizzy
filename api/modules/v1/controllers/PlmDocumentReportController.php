<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\ApiPlmDocument;
use app\api\modules\v1\models\PlmDocumentReport;
use app\modules\hr\models\HrDepartments;
use app\modules\references\models\Equipments;
use app\modules\references\models\Products;
use app\modules\references\models\Shifts;
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
        $post = $request->post();
        $type = $get["type"];

        $response = [
            "status" => true
        ];
        switch ($type){
            case "PLM_DOCUMENT_DATA":
                if ($post['is_search'] == false){
                    $response["hr_department_list"] =  HrDepartments::getList(true);
                    $response["shift_list"] =  Shifts::getList(false);
                    $response["equipment_list"] =  Equipments::getList(null,true);
                    $response["product_list"] =  Products::getList(null, true);
                }
                $dataProvider = PlmDocumentReport::getData($post);
                $response['items'] = $dataProvider->getModels();
                $response['pagination'] = $dataProvider->getPagination();
                $response['status'] = true;
                break;
        }

        return $response;
    }
}
