<?php

namespace app\api\modules\v1\controllers;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\references\models\Defects;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocItemProducts;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmDocuments;
use app\modules\plm\models\PlmProcessingTime;
use app\modules\plm\models\PlmStops;
use app\modules\plm\models\Reasons;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\EquipmentGroupRelationEquipment;
use app\modules\references\models\Equipments;
use app\modules\references\models\ProductLifecycle;
use app\modules\references\models\Products;
use app\modules\references\models\TimeTypesList;
use Yii;
use yii\db\Expression;
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
            case "SAVE_EQUIPMENT_GROUP":
                $group = $post['equipment_group'];
                $items = $post['relation'];
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    $newGroup = new EquipmentGroup();
                    $newGroup->setAttributes([
                        'name' => $group['name'],
                        'value' => $group['value'],
                        'status_id' => BaseModel::STATUS_ACTIVE
                    ]);
                    if ($newGroup->save()) {
                        $i = 1;
                        foreach ($items as $item) {
                            $newRelation = new EquipmentGroupRelationEquipment();
                            $newRelation->setAttributes([
                                'equipment_group_id' => $newGroup->id,
                                'equipment_id' => $item['equipment_id'],
                                'work_order' => $i++,
                                'status_id' => BaseModel::STATUS_ACTIVE
                            ]);
                            if ($newRelation->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                $response['errors'] = $newRelation->getErrors();
                                break;
                            }
                        }
                    } else {
                        $response['errors'] = $newGroup->getErrors();
                    }
                    if ($saved) {
                        $response['status'] = true;
                        $response['equipmentGroup'] = EquipmentGroup::getEquipmentGroupList(true, $newGroup->id);
                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $response['errors'] = $e->getMessage();
                }
                break;
            case "SAVE_PRODUCT_LIFECYCLE":
                $lifecycle = $post['lifecycle'];
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    $newLifecycle = new ProductLifecycle();
                    $newLifecycle->setAttributes([
                        'product_id' => $lifecycle['product_id'],
                        'equipment_group_id' => $lifecycle['equipment_group_id'],
                        'lifecycle' => $lifecycle['lifecycle'],
                        'bypass' => $lifecycle['bypass'],
                        'equipments' => true,
                        'status_id' => BaseModel::STATUS_ACTIVE
                    ]);
                    if ($newLifecycle->save()) {
                        $saved = true;
                    } else {
                        $response['errors'] = $newLifecycle->getErrors();
                    }
                    if ($saved) {
                        $response['status'] = true;
                        $response['productLifecycle'] = ProductLifecycle::getProductLifecycleList(true, $newLifecycle->id);
                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $response['errors'] = $e->getMessage();
                }
                break;
            case "SAVE_DOCUMENT":
                $document = $post['document'];
                $documentItems = $post['document_items'];
                $last = PlmDocuments::find()->orderBy(['id' => SORT_DESC])->one();
                if (!empty($last)) {
                    $last = $last['id'] + 1;
                } else {
                    $last = 1;
                }
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    $doc = new PlmDocuments();
                    if (!empty($document['id'])) {
                        $doc = PlmDocuments::findOne($document['id']);
                    }
                    $doc->setAttributes([
                        'doc_number' => "PD-".$last,
                        'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                        'hr_department_id' => $document['hr_department_id'],
                        'organisation_id' => $document['organisation_id'],
                        'shift_id' => $document['shift_id'],
                        'add_info' => $document['add_info'],
                        'status_id' => BaseModel::STATUS_ACTIVE
                    ]);
                    if ($doc->save()) {
                        foreach ($documentItems as $item) {
                            $plannedStopped = $item['planned_stopped'];
                            $unplannedStopped = $item['unplanned_stopped'];
                            if ($plannedStopped) {
                                $planStop = new PlmStops();
                                if ($item['planned_stop_id']) {
                                    $planStop = PlmStops::findOne($item['planned_stop_id']);
                                }
                                $planStop->setAttributes([
                                    'reason_id' => $plannedStopped['reason_id'],
                                    'begin_date' => date('Y-m-d H:i', strtotime($plannedStopped['begin_date'])),
                                    'end_time' => date('Y-m-d H:i', strtotime($plannedStopped['end_time'])),
                                    'add_info' => $plannedStopped['add_info'],
                                    'status_id' => BaseModel::STATUS_ACTIVE,
                                    'stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP
                                ]);
                                if ($planStop->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    $response['errors'] = $planStop->getErrors();
                                    $response['line'] = __LINE__;
                                    break;
                                }
                            }
                            if ($unplannedStopped) {
                                $unPlanStop = new PlmStops();
                                if ($item['unplanned_stop_id']) {
                                    $unPlanStop = PlmStops::findOne($item['unplanned_stop_id']);
                                }
                                $unPlanStop->setAttributes([
                                    'reason_id' => $unplannedStopped['reason_id'],
                                    'begin_date' => date('Y-m-d H:i', strtotime($unplannedStopped['begin_date'])),
                                    'end_time' => date('Y-m-d H:i', strtotime($unplannedStopped['end_time'])),
                                    'bypass' => $unplannedStopped['bypass'],
                                    'add_info' => $unplannedStopped['add_info'],
                                    'status_id' => BaseModel::STATUS_ACTIVE,
                                    'stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP
                                ]);
                                if ($unPlanStop->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    $response['errors'] = $unPlanStop->getErrors();
                                    $response['line'] = __LINE__;
                                    break;
                                }
                            }
                            if ($item['start_work'] && $item['end_work']) {
                                $processing = new PlmProcessingTime();
                                if ($item['processing_time_id']) {
                                    $processing = PlmProcessingTime::findOne($item['processing_time_id']);
                                }
                                $processing->setAttributes([
                                    'begin_date' => date("Y-m-d H:i", strtotime($item['start_work'])),
                                    'end_date' => date("Y-m-d H:i", strtotime($item['end_work'])),
                                    'status_id' => BaseModel::STATUS_ACTIVE
                                ]);
                                if ($processing->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    $response['errors'] = $processing->getErrors();
                                    $response['line'] = __LINE__;
                                    break;
                                }
                            }

                            $docItem = new PlmDocumentItems();
                            if ($item['id']) {
                                $docItem = PlmDocumentItems::findOne($item['id']);
                            }
                            $docItem->setAttributes([
                                'document_id' => $doc->id,
                                'planned_stop_id' => $planStop->id ?? "",
                                'unplanned_stop_id' => $unPlanStop->id ?? "",
                                'processing_time_id' => $processing->id ?? "",
                                'equipment_group_id' => $item['equipmentGroup']['value'] ?? "",
                            ]);
                            if ($docItem->save()) {
                                $products = $item['products'];
                                PlmDocItemProducts::deleteAll(['document_item_id' => $docItem->id]);
                                if ($products) {
                                    foreach ($products as $product) {
                                        $newProductItem = new PlmDocItemProducts();
                                        $newProductItem->setAttributes([
                                            'document_item_id' => $docItem->id,
                                            'product_id' => $product['product_id'],
                                            'product_lifecycle_id' => $product['product_lifecycle_id'],
                                            'qty' => $product['qty'],
                                            'fact_qty' => $product['fact_qty']
                                        ]);
                                        if ($newProductItem->save()) {
                                            $repaired = $product['repaired'];
                                            foreach ($repaired as $repair) {
                                                if ($repair['count']) {
                                                    $newDef = new PlmDocItemDefects();
                                                    $newDef->setAttributes([
                                                        'type' => BaseModel::DEFECT_REPAIRED,
                                                        'doc_item_id' => $docItem->id,
                                                        'defect_id' => $repair['value'],
                                                        'qty' => $repair['count'],
                                                        'status_id' => BaseModel::STATUS_ACTIVE,
                                                        'doc_item_product_id' => $newProductItem->id
                                                    ]);
                                                    if ($newDef->save()) {
                                                        $saved = true;
                                                    } else {
                                                        $saved = false;
                                                        $response['errors'] = $newDef->getErrors();
                                                        $response['line'] = __LINE__;
                                                        break 2;
                                                    }
                                                }
                                            }
                                            $scrapped = $product['scrapped'];
                                            if ($scrapped) {
                                                foreach ($scrapped as $scrap) {
                                                    if ($scrap['count']) {
                                                        $newDef = new PlmDocItemDefects();
                                                        $newDef->setAttributes([
                                                            'type' => BaseModel::DEFECT_SCRAPPED,
                                                            'doc_item_id' => $docItem->id,
                                                            'defect_id' => $scrap['value'],
                                                            'qty' => $scrap['count'],
                                                            'status_id' => BaseModel::STATUS_ACTIVE,
                                                            'doc_item_product_id' => $newProductItem->id
                                                        ]);
                                                        if ($newDef->save()) {
                                                            $saved = true;
                                                        } else {
                                                            $saved = false;
                                                            $response['line'] = __LINE__;
                                                            $response['errors'] = $newDef->getErrors();
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $saved = false;
                                $response['errors'] = $docItem->getErrors();
                                $response['line'] = __LINE__;
                                break;
                            }
                        }
                    } else {
                        $response['errors'] = $doc->getErrors();
                        $response['line'] = __LINE__;
                    }
                    if ($saved) {
                        $transaction->commit();
                        $response['status'] = true;
                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
                    } else {
                        $transaction->rollBack();
                        $response['line'] = __LINE__;
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $response['errors'] = $e->getMessage();
                    $response['line'] = __LINE__;
                }
                break;
            case "UPDATE":
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
                $response['status'] = true;
                $user_id = Yii::$app->user->id;

                $response['organisationList'] = HrDepartments::find()->alias('hd')->select([
                    'hd.id',
                    'hd.id as value',
                    'hd.name as label',
                ])->with([
                        'departments' => function($e) {
                            $e->from(['ch' => 'hr_departments'])->select([
                                'ch.id',
                                'ch.id as value',
                                'ch.name as label',
                                'ch.parent_id'
                            ])->with([
                                'shifts' => function($sh) {
                                    $sh->from(['dsh' => 'hr_department_rel_shifts'])->select([
                                        'sh.id as value',
                                        'sh.name as label',
                                        'dsh.hr_department_id'
                                    ])->leftJoin('shifts sh', 'dsh.shift_id = sh.id');
                                }
                            ]);
                        }
                ])->where(['hd.status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['IS', 'parent_id', new Expression('NULL')])
                    ->groupBy('hd.id')
                    ->asArray()->all();

                $response['equipmentGroupList'] = EquipmentGroup::getEquipmentGroupList();

                $response['productList'] = Products::find()->alias('p')->select([
                    'p.id as value', "p.name as label"
                ])->where(['p.status_id' => BaseModel::STATUS_ACTIVE])
                    ->groupBy('p.id')
                    ->asArray()->all();

               // $response['productLifecycleList'] = ProductLifecycle::getProductLifecycleList();


                $response['operatorList'] = HrEmployee::find()->asArray()->all();

                $response['equipmentList'] = Equipments::find()->alias('e')->select([
                    'e.id as value',
                    "e.name as label"
                ])->where(['e.status_id' => BaseModel::STATUS_ACTIVE])
                    ->groupBy('e.id')
                    ->asArray()->all();

                $response['reasonList'] = Reasons::find()->select([
                    'id as value',
                    "name_{$language} as label"
                ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
                    ->asArray()->all();

                $response['repaired'] = Defects::find()->select([
                    'id as value',
                    "name_{$language} as label",
                    "SUM(0) as count"
                ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['type' => BaseModel::DEFECT_REPAIRED])
                    ->groupBy('id')
                    ->asArray()->all();

                $response['scrapped'] = Defects::find()->select([
                    'id as value',
                    "name_{$language} as label",
                    "SUM(0) as count"
                ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['type' => BaseModel::DEFECT_SCRAPPED])
                    ->groupBy('id')
                    ->asArray()->all();

//                $response['shiftList'] = Shifts::find()->select([
//                    'id as value',
//                    "CONCAT(name, ' (', TO_CHAR(start_time, 'HH24:MI'), ' - ', TO_CHAR(end_time, 'HH24:MI'), ')') as label"
//                ])->asArray()->all();
//                $response['timeTypeList'] = TimeTypesList::find()->select([
//                    'id as value', 'name as label'
//                ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
//                    ->asArray()->all();

                if (!is_null($id)) {
                    $plm_document = \app\api\modules\v1\models\BaseModel::getDocumentElements($id);
                    if (!empty($plm_document)) {
                        $response['plm_document'] = $plm_document;
                    } else {
                        $response['status'] = false;
                        $response['message'] = Yii::t('app', 'Hujjat mavjud emas!');
                    }
                }

                $response['user_id'] = $user_id;
                $response['language'] = $language;
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
        $dataProvider = \app\api\modules\v1\models\BaseModel::getPlmDocuments([]);
        $response['documents'] = $dataProvider->getModels();
        $response['pagination'] = $dataProvider->getPagination();
        $response['status'] = true;
        return $response;
    }

}
