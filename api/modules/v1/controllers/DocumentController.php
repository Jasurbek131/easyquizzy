<?php

namespace app\api\modules\v1\controllers;

use app\models\BaseModel;
use app\modules\hr\models\UsersRelationHrDepartments;;

use app\modules\plm\models\Defects;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmDocuments;
use app\modules\plm\models\PlmProcessingTime;
use app\modules\plm\models\PlmStops;
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
                $document = $post['document'];
                $documentItems = $post['document_items'];
                $last = PlmDocuments::find()->orderBy(['id' => SORT_DESC])->one();
                if (!empty($last)) {
                    $last = $last->id + 1;
                } else {
                    $last = 1;
                }
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    $doc = new PlmDocuments();
                    if ($document['id']) {
                        $doc = PlmDocuments::findOne($document['id']);
                    }
                    $doc->setAttributes([
                        'doc_number' => "PD-".$last,
                        'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                        'hr_department_id' => $document['hr_department_id'],
                        'add_info' => $document['add_info'],
                        'status_id' => BaseModel::STATUS_ACTIVE
                    ]);
                    if ($doc->save()) {
                        foreach ($documentItems as $item) {
                            $plannedStopped = $item['planned_stopped'];
                            $unplannedStopped = $item['unplanned_stopped'];
                            if ($plannedStopped) {
                                $planStop = new PlmStops();
                                if ($item['planned_stop_id']){
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
                                    break;
                                }
                            }
                            if ($unplannedStopped) {
                                $unPlanStop = new PlmStops();
                                if ($item['unplanned_stop_id']){
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
                                    break;
                                }
                            }
                            if ($item['start_work'] && $item['end_work']) {
                                $processing = new PlmProcessingTime();
                                if ($item['processing_time_id']){
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
                                    break;
                                }
                            }

                            $docItem = new PlmDocumentItems();
                            if ($item['id']){
                                $docItem = PlmDocumentItems::findOne($item['id']);
                            }
                            $docItem->setAttributes([
                                'product_id' => $item['product_id'],
                                'planned_stop_id' => $planStop->id ?? "",
                                'unplanned_stop_id' => $unPlanStop->id ?? "",
                                'processing_time_id' => $processing->id ?? "",
                                'qty' => $item['qty'],
                                'fact_qty' => $item['fact_qty']
                            ]);
                            if ($docItem->save()) {
                                $repaired = $item['repaired'];
                                PlmDocItemDefects::deleteAll(['doc_item_id' => $docItem->id]);
                                if ($repaired) {
                                    foreach ($repaired as $repair) {
                                        if ($repair['count']) {
                                            $newDef = new PlmDocItemDefects();
                                            $newDef->setAttributes([
                                                'type' => BaseModel::DEFECT_REPAIRED,
                                                'doc_item_id' => $docItem->id,
                                                'defect_id' => $repair['value'],
                                                'qty' => $repair['count'],
                                                'status_id' => BaseModel::STATUS_ACTIVE
                                            ]);
                                            if ($newDef->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                $response['errors'] = $newDef->getErrors();
                                                break 2;
                                            }
                                        }
                                    }
                                }
                                $scrapped = $item['scrapped'];
                                if ($scrapped) {
                                    foreach ($scrapped as $scrap) {
                                        if ($scrap['count']) {
                                            $newDef = new PlmDocItemDefects();
                                            $newDef->setAttributes([
                                                'type' => BaseModel::DEFECT_SCRAPPED,
                                                'doc_item_id' => $docItem->id,
                                                'defect_id' => $scrap['value'],
                                                'qty' => $scrap['count'],
                                                'status_id' => BaseModel::STATUS_ACTIVE
                                            ]);
                                            if ($newDef->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                $response['errors'] = $newDef->getErrors();
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $saved = false;
                                $response['errors'] = $docItem->getErrors();
                                break;
                            }
                        }
                    } else {
                        $response['errors'] = $doc->getErrors();
                    }
                    if ($saved) {
                        $transaction->commit();
                        $response['status'] = true;
                        $response['message'] = Yii::t('app', "Muvaffaqiyatli saqlandi!");
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $response['errors'] = $e->getMessage();
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

                $response['organisationList'] = UsersRelationHrDepartments::find()->alias('urd')->select([
                    'hd.id as value', 'hd.name as label'
                ])
                    ->leftJoin('hr_departments hd', 'urd.hr_department_id = hd.id')
                    ->where(['hd.status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['urd.user_id' => $user_id])->andWhere(['urd.is_root' => true])
                    ->groupBy('hd.id')
                    ->asArray()->all();

                $response['departmentList'] = UsersRelationHrDepartments::find()->alias('urd')->select([
                    'hd.id as value', "hd.name as label"
                ])
                    ->leftJoin('hr_departments hd', 'urd.hr_department_id = hd.id')
                    ->where(['hd.status_id' => BaseModel::STATUS_ACTIVE])
                    ->andWhere(['urd.user_id' => $user_id])->andWhere(['urd.is_root' => false])
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
                $response['repaired'] = Defects::find()->select(['id as value', "name_{$language} as label", "SUM(0) as count"])
                    ->where(['status_id' => BaseModel::STATUS_ACTIVE])->andWhere(['type' => BaseModel::DEFECT_REPAIRED])
                    ->groupBy('id')->asArray()->all();
                $response['scrapped'] = Defects::find()->select(['id as value', "name_{$language} as label", "SUM(0) as count"])
                    ->where(['status_id' => BaseModel::STATUS_ACTIVE])->andWhere(['type' => BaseModel::DEFECT_SCRAPPED])
                    ->groupBy('id')->asArray()->all();

                if (!is_null($id)) {
                    $response['plm_document'] = PlmDocuments::find()->select([
                        'id', 'doc_number', 'reg_date', 'hr_department_id', 'add_info'
                    ])->with([
                        'plm_document_items' => function($q) use ($language) {
                            $q->from(['pdi' => 'plm_document_items'])
                                ->select(['pdi.*', 'ppt.begin_date as start_work', 'ppt.end_date as end_work'])->with([
                                    'planned_stopped' => function($e) {
                                        $e->from(['ps1' => 'plm_stops'])->select([
                                            'ps1.id', 'ps1.begin_date', 'ps1.end_time', 'ps1.add_info', 'ps1.reason_id'
                                        ])->where(['ps1.stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP]);
                                    },
                                    'unplanned_stopped' => function($e) {
                                        $e->from(['ps2' => 'plm_stops'])->select([
                                            'ps2.id', 'ps2.begin_date', 'ps2.end_time', 'ps2.add_info', 'ps2.reason_id', 'ps2.bypass'
                                        ])->where(['ps2.stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP]);
                                    },
                                    'repaired' => function($r) use ($language) {
                                        $r->from(['r' => 'plm_doc_item_defects'])->select([
                                            'r.defect_id as value', "d.name_{$language} as label", 'r.qty as count', 'r.doc_item_id'
                                        ])->leftJoin('defects d', 'r.defect_id = d.id')
                                        ->where(['r.type' => BaseModel::DEFECT_REPAIRED]);
                                    },
                                    'scrapped' => function($r) use ($language) {
                                        $r->from(['s' => 'plm_doc_item_defects'])->select([
                                            's.defect_id as value', "d.name_{$language} as label", 's.qty as count', 's.doc_item_id'
                                        ])->leftJoin('defects d', 's.defect_id = d.id')
                                            ->where(['s.type' => BaseModel::DEFECT_SCRAPPED]);
                                    },
                                ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id')->with([
                                    'products' => function($p) {
                                        $p->from(['p' => 'products'])->select(['p.equipment_group_id'])->with([
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
                                        ]);
                                    }
                                ]);
                        },
                    ])->where(['id' => $id])->asArray()->limit(1)->one();
                }

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
