<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\ApiProduct;
use app\models\BaseModel;
use app\modules\references\models\Equipments;
use app\modules\references\models\Products;
use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use app\api\modules\v1\components\CorsCustom;

/**
 * Country Controller API
 */
class ApiProductController extends ActiveController
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
                    'pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'list' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'total-pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'wrapper-item' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'accepted' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
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
     * @param $getData
     * @return array
     */
    public function conditions($getData)
    {

        $conditions = [];
        $conditions['page'] = 1;
        $conditions['limit'] = 10;
        $conditions['language'] = 'uz';
        $conditions['sort'] = 'DESC';

        if (!empty($getData)) {
            if (!empty($getData['limit'])) {
                $conditions['limit'] = $getData['limit'];
            }
            if (!empty($getData['page'])) {
                $conditions['page'] = $getData['page'];
            }
            if (!empty($getData['language'])) {
                $conditions['language'] = $getData['language'];
            }
            if (!empty($getData['sort'])) {
                $conditions['sort'] = $getData['sort'];
            }
        }
        return $conditions;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionIndex(){
        $request = Yii::$app->request;
        $get = $request->get();
        $post = $request->post();
        $type = $get["type"];

        $response = [
            "status" => true
        ];
        switch ($type){
            case "PRODUCT_DATA":
                if ($get['id']) {
                    $response['forms'] = ApiProduct::getProductFormData($get['id']);
                }
                $response["equipments_list"] = Equipments::getListForSelect();
                $response["status_list"] = BaseModel::getStatusList(null, true);
                $response["products_list"] = Products::getList(null, true);
                $response["equipments_group_type_list"] = BaseModel::getEquipmentGroupTypeList();
                break;
            case "PRODUCT_SAVE":
                $response = ApiProduct::saveApiProduct($post);
                break;
            case "PRODUCT_EQUIPMENT_SAVE":
                $response = ApiProduct::saveApiProductEquipment($post);
                break;
        }

        return $response;
    }


}
