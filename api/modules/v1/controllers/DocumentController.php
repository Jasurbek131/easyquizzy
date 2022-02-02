<?php

namespace app\api\modules\v1\controllers;

use app\models\BaseModel;
use app\modules\hr\models\UsersRelationHrDepartments;;

use app\modules\plm\models\Defects;
use app\modules\plm\models\Reasons;
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
class DocumentController extends ActiveController
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
                'class' => CorsCustom::className()
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'list' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'total-pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'wrapper-item' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'accepted' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                ],
            ],
            [
                'class' => ContentNegotiator::className(),
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
     */
    public function actionIndex(){
        $response['status'] = 'true';
        $post = Yii::$app->request->post();
        $response['post'] = $post;
        return $response;
    }

    /**
     * @param $type
     * @return array
     */
    public function actionSaveProperties($type): array
    {
        $response['message'] = Yii::t('app', "Ma'lumotlar yetarli emas!");
        $response['status'] = false;
        $post = Yii::$app->request->post();
        switch ($type) {
            case "SAVE_DOCUMENT":
                echo "<pre>";
                print_r($post);
                echo "</pre>";exit;
                break;
            case "UPDATE":
                break;
        }
        return $response;
    }

    /**
     * @param $type
     * @return array
     */
    public function actionFetchList($type): array
    {
        $response['status'] = false;
        $language = Yii::$app->language;
        $response['message'] = Yii::t('app', "Ma'lumotlar yetarli emas!");
        switch ($type) {
            case "CREATE_DOCUMENT":
                $response['status'] = true;
                $id = Yii::$app->user->id;

                $response['organisationList'] = UsersRelationHrDepartments::find()->alias('urd')->select([
                    'hd.id as value', 'hd.name as label'
                ])
                    ->leftJoin('hr_departments hd', 'urd.hr_department_id = hd.id')
                    ->where(['hd.status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['urd.user_id' => $id])->andWhere(['urd.is_root' => true])
                    ->groupBy('hd.id')
                    ->asArray()->all();

                $response['departmentList'] = UsersRelationHrDepartments::find()->alias('urd')->select([
                    'hd.id as value', "hd.name as label"
                ])
                    ->leftJoin('hr_departments hd', 'urd.hr_department_id = hd.id')
                    ->where(['hd.status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['urd.user_id' => $id])->andWhere(['urd.is_root' => false])
                    ->groupBy('hd.id')
                    ->asArray()->all();

                $response['productList'] = Products::find()->alias('p')->select([
                    'p.id as value', "p.name as label", "p.equipment_group_id"
                ])
                    ->with([
                        'equipmentGroup' => function($q) {
                            return $q->from(['eg' => 'equipment_group'])->select(['eg.id'])->with([
                                'equipmentGroupRelationEquipments' => function($e) {
                                    return $e->from(['ere' => 'equipment_group_relation_equipment'])
                                        ->select(['e.id', 'e.name', 'ere.equipment_group_id', 'ere.equipment_id'])
                                        ->leftJoin('equipments e', 'ere.equipment_id = e.id')
                                        ->orderBy(['ere.work_order' => SORT_ASC]);
                                }
                            ]);
                        }
                    ])
                    ->where(['p.status_id' => BaseModel::STATUS_ACTIVE])
                    ->groupBy('p.id')
                    ->asArray()->all();
                $response['reasonList'] = Reasons::find()->select(['id as value', "name_{$language} as label"])
                    ->where(['status_id' => BaseModel::STATUS_ACTIVE])
                    ->asArray()->all();
                $response['repaired'] = Defects::find()->select(['id as value', "name_{$language} as label"])
                    ->where(['status_id' => BaseModel::STATUS_ACTIVE])->andWhere(['type' => BaseModel::DEFECT_REPAIRED])
                    ->asArray()->all();
                $response['scrapped'] = Defects::find()->select(['id as value', "name_{$language} as label"])
                    ->where(['status_id' => BaseModel::STATUS_ACTIVE])->andWhere(['type' => BaseModel::DEFECT_SCRAPPED])
                    ->asArray()->all();

                $response['user_id'] = $id;
                $response['language'] = $language;
                break;
            case "UPDATE_DOCUMENT":
                $response['status'] = true;
                break;
        }
        return $response;
    }

}
