<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\ApiPlmDocument;
use app\models\BaseModel;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\references\models\Defects;
use app\modules\references\models\Reasons;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\EquipmentGroupRelationEquipment;
use app\modules\references\models\Equipments;
use app\modules\references\models\ProductLifecycle;
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
     * @throws yii\db\Exception
     */
    public function actionSaveProperties($type): array
    {
        $response['message'] = Yii::t('app', "Ma'lumotlar yetarli emas!");
        $response['status'] = false;
        $response['line'] = 0;
        $post = Yii::$app->request->post();
        switch ($type) {
//            case "SAVE_EQUIPMENT_GROUP":
//                $group = $post['equipment_group'];
//                $items = $post['relation'];
//                $transaction = Yii::$app->db->beginTransaction();
//                $saved = false;
//                try {
//                    $newGroup = new EquipmentGroup();
//                    $newGroup->setAttributes([
//                        'name' => $group['name'],
//                        'value' => $group['value'],
//                        'status_id' => BaseModel::STATUS_ACTIVE
//                    ]);
//                    if ($newGroup->save()) {
//                        $i = 1;
//                        foreach ($items as $item) {
//                            $newRelation = new EquipmentGroupRelationEquipment();
//                            $newRelation->setAttributes([
//                                'equipment_group_id' => $newGroup->id,
//                                'equipment_id' => $item['equipment_id'],
//                                'work_order' => $i++,
//                                'status_id' => BaseModel::STATUS_ACTIVE
//                            ]);
//                            if ($newRelation->save()) {
//                                $saved = true;
//                            } else {
//                                $saved = false;
//                                $response['errors'] = $newRelation->getErrors();
//                                break;
//                            }
//                        }
//                    } else {
//                        $response['errors'] = $newGroup->getErrors();
//                    }
//                    if ($saved) {
//                        $response['status'] = true;
//                        $response['equipmentGroup'] = EquipmentGroup::getEquipmentGroupList(true, $newGroup->id);
//                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
//                        $transaction->commit();
//                    } else {
//                        $transaction->rollBack();
//                    }
//                } catch (\Exception $e) {
//                    $transaction->rollBack();
//                    $response['errors'] = $e->getMessage();
//                }
//                break;
//            case "SAVE_PRODUCT_LIFECYCLE":
//                $lifecycle = $post['lifecycle'];
//                $transaction = Yii::$app->db->beginTransaction();
//                $saved = false;
//                try {
//                    $newLifecycle = new ProductLifecycle();
//                    $newLifecycle->setAttributes([
//                        'product_id' => $lifecycle['product_id'],
//                        'equipment_group_id' => $lifecycle['equipment_group_id'],
//                        'lifecycle' => $lifecycle['lifecycle'],
//                        'bypass' => $lifecycle['bypass'],
//                        'equipments' => true,
//                        'status_id' => BaseModel::STATUS_ACTIVE
//                    ]);
//                    if ($newLifecycle->save()) {
//                        $saved = true;
//                    } else {
//                        $response['errors'] = $newLifecycle->getErrors();
//                    }
//                    if ($saved) {
//                        $response['status'] = true;
//                        $response['productLifecycle'] = ProductLifecycle::getProductLifecycleList(true, $newLifecycle->id);
//                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
//                        $transaction->commit();
//                    } else {
//                        $transaction->rollBack();
//                    }
//                } catch (\Exception $e) {
//                    $transaction->rollBack();
//                    $response['errors'] = $e->getMessage();
//                }
//                break;
            case "SAVE_DOCUMENT":
                $response = ApiPlmDocument::saveData($post);
                break;
            case "DELETE_DOCUMENT_ITEM":
                $response = ApiPlmDocument::deleteDocumentItem($post);
                break;
            case "SAVE_MODAL":
                $response = ApiPlmDocument::saveModalData($post);
                break;
        }
        return $response;
    }

    /**
     * @param $type
     * @param null $id
     * @return array
     */
    public function actionFetchList($type, $id = null): array
    {
        $response['status'] = false;
        $language = Yii::$app->language;
        $response['message'] = Yii::t('app', "Ma'lumotlar yetarli emas!");
        switch ($type) {
            case "CREATE_DOCUMENT":
                $response = [
                    'status' => true,
//                    'organisationList' => HrDepartments::getOrganisationListWithSmenaByUser(),
                    'departmentList' => HrDepartments::getDepartmentListWithSmenaByUser(),
                    'equipmentGroupList' => EquipmentGroup::getEquipmentGroupList(),
                    'user_id' => Yii::$app->user->id,
                    'language' => $language,
                    'today' => /*date('Y.m.d H:i:s')*/ '',
                    'reasonList' => Reasons::getList(),
                    'repaired' => Defects::getListByType(BaseModel::DEFECT_REPAIRED),
                    'scrapped' => Defects::getListByType(BaseModel::DEFECT_SCRAPPED),
                ];

//                $response['productList'] = Products::find()->alias('p')->select([
//                    'p.id as value', "p.name as label"
//                ])->where(['p.status_id' => BaseModel::STATUS_ACTIVE])
//                    ->groupBy('p.id')
//                    ->asArray()->all();

               // $response['productLifecycleList'] = ProductLifecycle::getProductLifecycleList();


//                $response['operatorList'] = HrEmployee::find()->asArray()->all();

//                $response['equipmentList'] = Equipments::find()
//                    ->alias('e')
//                    ->select([
//                        'e.id as value',
//                        "e.name as label"
//                    ])->where(['e.status_id' => BaseModel::STATUS_ACTIVE])
//                    ->groupBy('e.id')
//                    ->asArray()
//                    ->all();

//                $response['shiftList'] = Shifts::find()->select([
//                    'id as value',
//                    "CONCAT(name, ' (', TO_CHAR(start_time, 'HH24:MI'), ' - ', TO_CHAR(end_time, 'HH24:MI'), ')') as label"
//                ])->asArray()->all();
//                $response['timeTypeList'] = TimeTypesList::find()->select([
//                    'id as value', 'name as label'
//                ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
//                    ->asArray()->all();

                if (!is_null($id)) {
                    $plm_document = \app\api\modules\v1\models\ApiPlmDocument::getDocumentElements($id);
                    if (!empty($plm_document)) {
                        $response['plm_document'] = $plm_document;
                    } else {
                        $response['status'] = false;
                        $response['message'] = Yii::t('app', 'Hujjat mavjud emas!');
                    }
                }

                break;
            case "INDEX_DOCUMENT":
                break;
        }
        return $response;
    }

    /**
     * @return array
     */
    public function actionSearch(): array
    {
        $dataProvider = \app\api\modules\v1\models\ApiPlmDocument::getPlmDocuments([]);
        $response['documents'] = $dataProvider->getModels();
        $response['pagination'] = $dataProvider->getPagination();
        $response['status'] = true;
        return $response;
    }

}
