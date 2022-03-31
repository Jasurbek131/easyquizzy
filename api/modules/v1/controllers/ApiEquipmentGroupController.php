<?php


namespace app\api\modules\v1\controllers;


use app\api\modules\v1\components\CorsCustom;
use app\api\modules\v1\models\ApiEquipmentGroup;
use app\models\BaseModel;
use app\modules\references\models\Equipments;
use app\modules\references\models\EquipmentTypes;
use app\modules\references\models\Products;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiEquipmentGroupController extends ActiveController
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
            case "EQUIPMENT_GROUP_DATA":
                if ($get['id']) {
                    $response['forms']["groups"][0] = ApiEquipmentGroup::getEquipmentGroupFormData($get['id']);
                }
                $response["equipments_list"] = Equipments::getListForSelect();
                $response["status_list"] = BaseModel::getStatusList(null, true);
                $response["products_list"] = Products::getList(null, true);
                $response["equipments_type_list"] = EquipmentTypes::getList(null, true);
                $response['messages'] = [
                    'equipments' => Yii::t('app', 'Equipments'),
                    'group_type' => Yii::t('app', 'Equipments type'),
                    'lifecycle' => Yii::t('app', 'Cycle time (s)'),
                    'bypass' => Yii::t('app', 'Bypass (s)'),
                    'group_name' => Yii::t('app', 'Group Name'),
                    'header' => Yii::t('app', 'Product data & Group equipment'),
                    'back' => Yii::t('app', 'Back'),
                    'products' => Yii::t('app', 'Products'),
                ];
                break;
            case "PRODUCT_SAVE":
                $response = ApiEquipmentGroup::saveApiProduct($post);
                break;
            case "EQUIPMENT_GROUP_SAVE":
                $response = ApiEquipmentGroup::saveApiEquipmentGroup($post);
                break;
            case "EQUIPMENT_GROUP_DELETE":
                $response = ApiEquipmentGroup::deleteApiEquipmentGroup($post);
                break;
            case "PRODUCT_DELETE":
                $response = ApiEquipmentGroup::deleteApiProduct($post);
                break;
        }

        return $response;
    }
}