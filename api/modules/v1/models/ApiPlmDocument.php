<?php


namespace app\api\modules\v1\models;


use app\api\modules\v1\models\ApiPlmDocumentInterface;
use app\models\BaseModel;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocItemEquipments;
use app\modules\plm\models\PlmDocItemProducts;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmDocuments;
use app\modules\plm\models\PlmNotificationRelDefect;
use app\modules\plm\models\PlmNotificationsList;
use app\modules\plm\models\PlmProcessingTime;
use app\modules\plm\models\PlmSectorList;
use app\modules\plm\models\PlmStops;
use app\modules\references\models\Defects;
use app\modules\references\models\EquipmentGroup;
use app\widgets\Language;
use Yii;
use yii\data\ActiveDataProvider;

class ApiPlmDocument extends PlmDocuments implements ApiPlmDocumentInterface
{

    /**
     * @param $post
     * @return array
     */
    public static function saveData($post): array
    {
        $document = $post['document'];
        $documentItems = $post['document_items'];

        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            $doc = new PlmDocuments();
            if (!empty($document['id']))
                $doc = PlmDocuments::findOne($document['id']);
            $doc->setAttributes([
                'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                'hr_department_id' => $document['hr_department_id'],
                'organisation_id' => $document['organisation_id'],
                'shift_id' => $document['shift_id'],
                'add_info' => $document['add_info'],
                'status_id' => BaseModel::STATUS_ACTIVE
            ]);
            if (!$doc->save())
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Doc not saved'),
                    'errors' => $doc->getErrors(),
                    'line' => __LINE__
                ];


            if ($response['status']) {
                foreach ($documentItems as $item) {
//                    $plannedStopped = $item['planned_stopped'];
//                    $unplannedStopped = $item['unplanned_stopped'];
//
//                    /**
//                     * Planned stop
//                     */
//                    if (!empty($plannedStopped)) {
//                        $planStop = new PlmStops();
//                        if ($item['planned_stop_id'])
//                            $planStop = PlmStops::findOne($item['planned_stop_id']);
//                        $planStop->setAttributes([
//                            'reason_id' => $plannedStopped['reason_id'],
//                            'begin_date' => date('Y-m-d H:i', strtotime($plannedStopped['begin_date'])),
//                            'end_time' => date('Y-m-d H:i', strtotime($plannedStopped['end_time'])),
//                            'add_info' => $plannedStopped['add_info'],
//                            'status_id' => BaseModel::STATUS_ACTIVE,
//                            'stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP
//                        ]);
//                        if (!$planStop->save()) {
//                            $response = [
//                                'status' => false,
//                                'line' => __LINE__,
//                                'errors' => $planStop->getErrors(),
//                                'message' => Yii::t('app', 'Planned stop not saved'),
//                            ];
//                            break;
//                        }
//                    }
//
//                    /**
//                     * Un planned stop
//                     */
//                    if (!empty($unplannedStopped) && $response['status']) {
//                        $unPlanStop = new PlmStops();
//                        if ($item['unplanned_stop_id'])
//                            $unPlanStop = PlmStops::findOne($item['unplanned_stop_id']);
//                        $unPlanStop->setAttributes([
//                            'reason_id' => $unplannedStopped['reason_id'],
//                            'begin_date' => date('Y-m-d H:i', strtotime($unplannedStopped['begin_date'])),
//                            'end_time' => date('Y-m-d H:i', strtotime($unplannedStopped['end_time'])),
//                            'bypass' => $unplannedStopped['bypass'],
//                            'add_info' => $unplannedStopped['add_info'],
//                            'status_id' => BaseModel::STATUS_ACTIVE,
//                            'stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP
//                        ]);
//                        if (!$unPlanStop->save()) {
//                            $response = [
//                                'status' => false,
//                                'line' => __LINE__,
//                                'errors' => $unPlanStop->getErrors(),
//                                'message' => Yii::t('app', 'Unplanned stop not saved'),
//                            ];
//                            break;
//                        }
//                    }

                    if ($response['status'] && $item['start_work'] && $item['end_work']) {
                        $processing = new PlmProcessingTime();
                        if ($item['processing_time_id'])
                            $processing = PlmProcessingTime::findOne($item['processing_time_id']);
                        $processing->setAttributes([
                            'begin_date' => date("Y-m-d H:i", strtotime($item['start_work'])),
                            'end_date' => date("Y-m-d H:i", strtotime($item['end_work'])),
                            'status_id' => BaseModel::STATUS_ACTIVE
                        ]);
                        if (!$processing->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $processing->getErrors(),
                                'message' => Yii::t('app', 'Processing time not saved'),
                            ];
                            break;
                        }
                    }

                    if ($response['status']) {
                        $docItem = new PlmDocumentItems();
                        if ($item['id'])
                            $docItem = PlmDocumentItems::findOne($item['id']);
                        $docItem->setAttributes([
                            'document_id' => $doc->id,
                            'processing_time_id' => $processing->id ?? "",
                            'equipment_group_id' => $item['equipmentGroup']['value'] ?? "",
                        ]);
                        if (!$docItem->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $docItem->getErrors(),
                                'message' => Yii::t('app', 'Doc item not saved'),
                            ];
                            break;
                        }

                        $products = $item['products'];
                        PlmDocItemProducts::deleteAll(['document_item_id' => $docItem->id]);
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $newProductItem = new PlmDocItemProducts();
                                $newProductItem->setAttributes([
                                    'document_item_id' => $docItem->id,
                                    'product_id' => $product['product_id'],
                                    'product_lifecycle_id' => $product['product_lifecycle_id'] ?? "",
                                    'qty' => $product['qty'],
                                    'fact_qty' => $product['fact_qty'],
                                    'lifecycle' => $product['lifecycle'],
                                    'bypass' => $product['bypass'],
                                    'target_qty' => $product['target_qty'],
                                ]);
                                if (!$newProductItem->save()) {
                                    $response = [
                                        'status' => false,
                                        'line' => __LINE__,
                                        'errors' => $newProductItem->getErrors(),
                                        'message' => Yii::t('app', 'Doc item product not saved'),
                                    ];
                                    break 2;
                                }

                                $repaired = $product['repaired'] ?? [];
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
                                        if (!$newDef->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $newDef->getErrors(),
                                                'message' => Yii::t('app', 'Doc item repaired not saved'),
                                            ];
                                            break 3;
                                        }
                                    }
                                }

                                $scrapped = $product['scrapped'] ?? [];
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
                                        if (!$newDef->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $newDef->getErrors(),
                                                'message' => Yii::t('app', 'Doc item scrapped not saved'),
                                            ];
                                            break 3;
                                        }
                                    }
                                }

                            }
                        }

                        $equipments = $item['equipments'];
                        if (!empty($equipments)) {
                            PlmDocItemEquipments::deleteAll(['document_item_id' => $docItem->id]);
                            foreach ($equipments as $equipment) {
                                $docItemEquipment = new PlmDocItemEquipments([
                                    'document_item_id' => $docItem->id,
                                    'equipment_id' => $equipment['value'],
                                ]);
                                if (!$docItemEquipment->save()) {
                                    $response = [
                                        'status' => false,
                                        'line' => __LINE__,
                                        'errors' => $docItemEquipment->getErrors(),
                                        'message' => Yii::t('app', 'Doc item equipment not saved'),
                                    ];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }

            if ($response['status']) {
                $response["doc_item_id"] = $docItem->id ?? "";
                $response["doc_id"] = $doc->id ?? "";
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'errors' => $e->getMessage(),
                'line' => __LINE__,
            ];
        }
        return $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function saveModalData($post): array
    {
        $document = $post['document'];
        $documentItems = $post['document_items'];

        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            $doc = new PlmDocuments();
            if (!empty($document['id']))
                $doc = PlmDocuments::findOne($document['id']);
            $doc->setAttributes([
                'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                'hr_department_id' => $document['hr_department_id'],
                'organisation_id' => $document['organisation_id'],
                'shift_id' => $document['shift_id'],
                'add_info' => $document['add_info'],
                'status_id' => BaseModel::STATUS_ACTIVE
            ]);
            if (!$doc->save())
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Doc not saved'),
                    'errors' => $doc->getErrors(),
                    'line' => __LINE__
                ];

            if ($response['status']) {
                foreach ($documentItems as $item) {
                    $docItem = new PlmDocumentItems();
                    if ($item['id'])
                        $docItem = PlmDocumentItems::findOne($item['id']);
                    $docItem->setAttributes([
                        'document_id' => $doc->id,
                        'planned_stop_id' => $planStop->id ?? "",
                        'unplanned_stop_id' => $unPlanStop->id ?? "",
                        'processing_time_id' => $processing->id ?? "",
                        'equipment_group_id' => $item['equipmentGroup']['value'] ?? "",
                    ]);
                    if (!$docItem->save()) {
                        $response = [
                            'status' => false,
                            'line' => __LINE__,
                            'errors' => $docItem->getErrors(),
                            'message' => Yii::t('app', 'Plm Doc item not saved'),
                        ];
                        break;
                    }
                    $plannedStopped = $item['planned_stopped']; // rejali to'xtalish
                    $unplannedStopped = $item['unplanned_stopped']; // rejasiz to'xtalish
                    $products = $item['products'];

                    /** WORKING_TIME start **/
                    if ($response['status'] && $item['start_work'] && $item['end_work']) {
                        $plmNotifications = new PlmNotificationsList();
                        $plmNotifications->setAttributes([
                            'plm_doc_item_id' => $docItem->id,
                            'begin_time' => date("Y-m-d H:i", strtotime($item['start_work'])),
                            'end_time' => date("Y-m-d H:i", strtotime($item['end_work'])),
                            'status_id' => BaseModel::STATUS_ACTIVE,
                            'plm_sector_list_id' => PlmSectorList::getSectorId('WORKING_TIME'), // WORKING_TIME ID
                        ]);
                        if (!$plmNotifications->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $plmNotifications->getErrors(),
                                'message' => Yii::t('app', 'Working notification time not saved'),
                            ];
                            break;
                        }
                    }
                    /** WORKING_TIME end **/

                    if ($response['status']) {
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                /** REPAIRED(DEFECTS) start **/
                                $repaired = $product['repaired'] ?? [];
                                if ($repaired) {
                                    $plmNotifications = new PlmNotificationsList();
                                    $plmNotifications->setAttributes([
                                        'plm_doc_item_id' => $docItem->id,
                                        'defect_type_id' => Defects::REPAIRED_TYPE,
                                        'status_id' => BaseModel::STATUS_ACTIVE,
                                        'plm_sector_list_id' => PlmSectorList::getSectorId('REPAIRED'), // REPAIRED ID
                                    ]);
                                    if (!$plmNotifications->save()) {
                                        $response = [
                                            'status' => false,
                                            'line' => __LINE__,
                                            'errors' => $plmNotifications->getErrors(),
                                            'message' => Yii::t('app', 'PlmNotificationsList not saved'),
                                        ];
                                        break;
                                    }
                                    foreach ($repaired as $repair) {
                                        if ($repair['count']) {
                                            $plmNotificationRelDefects = new PlmNotificationRelDefect();
                                            $plmNotificationRelDefects->setAttributes([
                                                'plm_notification_id' => $plmNotifications->id,
                                                'defect_id' => $repair['value'],
                                                'defect_count' => $repair['count'],
                                                'status_id' => BaseModel::STATUS_ACTIVE,
                                            ]);
                                            if (!$plmNotificationRelDefects->save()) {
                                                $response = [
                                                    'status' => false,
                                                    'line' => __LINE__,
                                                    'errors' => $plmNotifications->getErrors(),
                                                    'message' => Yii::t('app', 'PlmNotificationRelDefect not saved'),
                                                ];
                                                break;
                                            }
                                        }
                                    }

                                }
                                /** REPAIRED(DEFECTS) end **/

                                /** INVALID(DEFECTS) start **/
                                $scrapped = $product['scrapped'] ?? [];
                                if ($scrapped) {
                                    $plmNotifications = new PlmNotificationsList();
                                    $plmNotifications->setAttributes([
                                        'plm_doc_item_id' => $docItem->id,
                                        'defect_type_id' => Defects::INVALID_TYPE,
                                        'status_id' => BaseModel::STATUS_ACTIVE,
                                        'plm_sector_list_id' => PlmSectorList::getSectorId('INVALID'), // INVALID ID
                                    ]);
                                    if (!$plmNotifications->save()) {
                                        $response = [
                                            'status' => false,
                                            'line' => __LINE__,
                                            'errors' => $plmNotifications->getErrors(),
                                            'message' => Yii::t('app', 'PlmNotificationsList not saved'),
                                        ];
                                        break;
                                    }
                                    foreach ($scrapped as $scrap) {
                                        if ($scrap['count']) {
                                            $plmNotificationRelDefects = new PlmNotificationRelDefect();
                                            $plmNotificationRelDefects->setAttributes([
                                                'plm_notification_id' => $plmNotifications->id,
                                                'defect_id' => $scrap['value'],
                                                'defect_count' => $scrap['count'],
                                                'status_id' => BaseModel::STATUS_ACTIVE,
                                            ]);
                                            if (!$plmNotificationRelDefects->save()) {
                                                $response = [
                                                    'status' => false,
                                                    'line' => __LINE__,
                                                    'errors' => $plmNotifications->getErrors(),
                                                    'message' => Yii::t('app', 'PlmNotificationRelDefect not saved'),
                                                ];
                                                break;
                                            }
                                        }
                                    }
                                }

                                /** INVALID(DEFECTS) start **/
                            }
                        }
                    }

                    /** PLANNED start **/

                    if (!empty($plannedStopped)) {
                        $plmNotifications = new PlmNotificationsList();
                        $plmNotifications->setAttributes([
                            'plm_doc_item_id' => $docItem->id,
                            'reason_id' => $plannedStopped['reason_id'],
                            'begin_time' => date("Y-m-d H:i", strtotime($item['begin_date'])),
                            'end_time' => date("Y-m-d H:i", strtotime($item['end_time'])),
                            'status_id' => BaseModel::STATUS_ACTIVE,
                            'add_info' => $plannedStopped['add_info'],
                            'plm_sector_list_id' => PlmSectorList::getSectorId('PLANNED'),// PLANNED ID
                        ]);
                        if (!$plmNotifications->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $plmNotifications->getErrors(),
                                'message' => Yii::t('app', 'Planned notification stop not saved'),
                            ];
                            break;
                        }
                    }

                    /** PLANNED end **/

                    /** UNPLANNED start **/
                    if (!empty($unplannedStopped) && $response['status']) {
                        $plmNotifications = new PlmNotificationsList();
                        $plmNotifications->setAttributes([
                            'plm_doc_item_id' => $docItem->id,
                            'reason_id' => $unplannedStopped['reason_id'],
                            'begin_time' => date("Y-m-d H:i", strtotime($item['begin_date'])),
                            'end_time' => date("Y-m-d H:i", strtotime($item['end_time'])),
                            'status_id' => BaseModel::STATUS_ACTIVE,
                            'add_info' => $unplannedStopped['add_info'],
                            'plm_sector_list_id' => PlmSectorList::getSectorId('UNPLANNED'),// UNPLANNED ID
                        ]);
                        if (!$plmNotifications->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $plmNotifications->getErrors(),
                                'message' => Yii::t('app', 'Unplanned notification stop not saved'),
                            ];
                            break;
                        }
                    }
                    /** UNPLANNED end **/
                }
            }

            if ($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();

        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'errors' => $e->getMessage(),
                'line' => __LINE__,
            ];
        }
        return $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function deleteDocumentItem($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Deleted'),
        ];
        try {
            if (!empty($post["plm_document_items"]) && !empty($post["plm_document_items"]["id"])) {

                $docItem = PlmDocumentItems::findOne(["id" => $post["plm_document_items"]["id"]]);
                if (!empty($docItem)) {
                    PlmDocItemDefects::deleteAll(["doc_item_id" => $docItem->id]);
                    PlmDocItemEquipments::deleteAll(["document_item_id" => $docItem->id]);
                    PlmDocItemProducts::deleteAll(["document_item_id" => $docItem->id]);
                    PlmNotificationsList::deleteAll(["plm_doc_item_id" => $docItem->id]);

                    $docItem->setAttributes([
                        "planned_stop_id" => "",
                        "unplanned_stop_id" => "",
                        "processing_time_id" => "",
                    ]);
                    if (!$docItem->save())
                        $response = [
                            'status' => false,
                            'errors' => $docItem->getErrors(),
                            'message' => Yii::t('app', 'Doc item saved'),
                        ];

                    if (!empty($docItem->planned_stop_id))
                        PlmStops::deleteAll(['id' => $docItem->planned_stop_id]);

                    if (!empty($docItem->unplanned_stop_id))
                        PlmStops::deleteAll(['id' => $docItem->unplanned_stop_id]);

                    if (!empty($docItem->processing_time_id))
                        PlmProcessingTime::findAll(["id" => $docItem->processing_time_id]);

                    if ($docItem->delete() == false)
                        $response = [
                            'status' => false,
                            'message' => Yii::t('app', 'Not deleted'),
                        ];
                }
            }

            if ($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();

        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getDocumentElements($id): array
    {
        $language = Yii::$app->language;
        $data = PlmDocuments::find()
            ->select([
                'id', 'doc_number', 'reg_date', 'hr_department_id', 'add_info', 'shift_id', 'organisation_id'
            ])->with([
                'plm_document_items' => function ($q) use ($language) {
                    $q->from(['pdi' => 'plm_document_items'])
                        ->select([
                            'pdi.*',
                            'ppt.begin_date as start_work',
                            'ppt.end_date as end_work',
                        ])->with([
                            'products' => function ($p) use ($language) {
                                $p->from(['p' => 'plm_doc_item_products'])->select([
                                    'p.id',
                                    'p.product_lifecycle_id',
                                    'p.product_id',
                                    'p.product_id as value',
                                    'p.qty',
                                    'p.fact_qty',
                                    'p.lifecycle',
                                    'p.bypass',
                                    'p.target_qty',
                                    'p.document_item_id',
                                ])
                                    ->with([
                                        'repaired' => function ($r) use ($language) {
                                            $r->from(['r' => 'plm_doc_item_defects'])->select([
                                                'r.defect_id as value', "d.name_{$language} as label", 'r.qty as count', 'r.doc_item_product_id'
                                            ])->leftJoin('defects d', 'r.defect_id = d.id')
                                                ->where(['r.type' => \app\models\BaseModel::DEFECT_REPAIRED]);
                                        },
                                        'scrapped' => function ($r) use ($language) {
                                            $r->from(['s' => 'plm_doc_item_defects'])->select([
                                                's.defect_id as value', "d.name_{$language} as label", 's.qty as count', 's.doc_item_product_id'
                                            ])->leftJoin('defects d', 's.defect_id = d.id')
                                                ->where(['s.type' => \app\models\BaseModel::DEFECT_SCRAPPED]);
                                        },
                                    ]);
                            },
                            'planned_stops' => function ($e) use ($language) {
                                $e->from(['ps1' => 'plm_stops'])
                                    ->select([
                                        'ps1.id',
                                        'ps1.begin_date',
                                        "to_char(ps1.begin_date, 'DD.MM.YYYY HH24:MI:SS') as format_begin_date",
                                        "to_char(ps1.end_time, 'DD.MM.YYYY HH24:MI:SS') as format_end_time",
                                        'ps1.end_time',
                                        'ps1.add_info',
                                        'ps1.reason_id',
                                        "ps1.document_item_id",
                                        "r.name_{$language} as reason_name",
                                    ])
                                    ->leftJoin(["r" => "reasons"], "ps1.reason_id = r.id")
                                    ->where([
                                        'ps1.stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP,
                                        'ps1.status_id' => BaseModel::STATUS_ACTIVE,
                                    ]);
                            },
                            'unplanned_stops' => function ($e) use ($language){
                                $e->from(['ps2' => 'plm_stops'])
                                    ->select([
                                        'ps2.id',
                                        'ps2.begin_date',
                                        'ps2.end_time',
                                        'ps2.add_info',
                                        'ps2.reason_id',
                                        'ps2.bypass',
                                        "ps2.document_item_id",
                                        "r.name_{$language} as reason_name",
                                    ])
                                    ->leftJoin(["r" => "reasons"], "ps2.reason_id = r.id")
                                    ->where([
                                        'ps2.stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP,
                                        'ps2.status_id' => BaseModel::STATUS_ACTIVE,
                                    ]);
                            },
                            'equipmentGroup' => function ($eg) {
                                $eg->from(['eg' => 'equipment_group'])->select(['eg.id', 'eg.id as value'])
                                    ->with([
                                        'equipments' => function ($e) {
                                            $e->from(['egr' => 'equipment_group_relation_equipment'])->select([
                                                'egr.equipment_id',
                                                'egr.equipment_group_id',
                                                'e.name as label',
                                                'e.id as value'
                                            ])->leftJoin('equipments e', 'egr.equipment_id = e.id');
                                        },
                                        'cycles' => function ($pl) {
                                            $pl->from(['pl' => 'product_lifecycle'])
                                                ->select([
                                                    'pl.id as product_lifecycle_id',
                                                    'pl.lifecycle',
                                                    'pl.bypass',
                                                    'pl.equipment_group_id',
                                                    'pl.product_group_id',
                                                ])
                                                ->with(["productGroup.products" => function ($rp) {
                                                    $rp->from(["rp" => "references_product_group_rel_product"])
                                                        ->select([
                                                            "rp.product_group_id",
                                                            "rp.product_id",
                                                            "p.name as label",
                                                            "p.id as value",
                                                        ])
                                                        ->leftJoin('products p', 'rp.product_id = p.id');
                                                }]);
                                        }

                                    ])->where(['eg.status_id' => BaseModel::STATUS_ACTIVE]);
                            },
                            'equipments' => function ($e) {
                                $e->from(['pdie' => "plm_doc_item_equipments"])
                                    ->select([
                                        "pdie.document_item_id",
                                        "e.id as value",
                                        "e.name as label"
                                    ])
                                    ->leftJoin(["e" => "equipments"], 'pdie.equipment_id = e.id');
                            },
                        ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id');
                },
                'hrDepartments' => function ($hd) {
                    $hd->from(['hd' => 'hr_departments'])->select([
                        'hd.id as value', 'hd.name as label'
                    ]);
                },
                'shifts' => function ($sh) {
                    $sh->from(['sh' => 'shifts'])->select([
                        'sh.id as value', 'sh.name as label'
                    ]);
                },
            ])->where(['id' => (integer)$id])
            ->asArray()
            ->limit(1)
            ->one();

        if ($data && $data["plm_document_items"]) {
            foreach ($data["plm_document_items"] as $key => $item) {
                $data["plm_document_items"][$key]["equipmentGroup"] = EquipmentGroup::getProductList([$item["equipmentGroup"]], $item["equipmentGroup"], 0);
                unset($data["plm_document_items"][$key]["equipmentGroup"]["cycles"]);
            }
        }
        return $data ?? [];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public static function getPlmDocuments($params)
    {
        $pageSize = $params['page_size'] ?? 20;
        $language = Yii::$app->language;
        $plm_document = PlmDocuments::find()
            ->alias('pd')
            ->select([
                "pd.*", "sh.name as shift", 'hd.name as department'
            ])->with([
                'plm_document_items' => function ($q) use ($language) {
                    $q->from(['pdi' => 'plm_document_items'])
                        ->select(['pdi.*', 'ppt.begin_date as start_work', 'ppt.end_date as end_work'])->with([
                            'planned_stopped' => function ($e) {
                                $e->from(['ps1' => 'plm_stops'])->select([
                                    'ps1.id', 'ps1.begin_date', 'ps1.end_time', 'ps1.add_info', 'ps1.reason_id'
                                ])->where(['ps1.stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP]);
                            },
                            'unplanned_stopped' => function ($e) {
                                $e->from(['ps2' => 'plm_stops'])->select([
                                    'ps2.id', 'ps2.begin_date', 'ps2.end_time', 'ps2.add_info', 'ps2.reason_id', 'ps2.bypass'
                                ])->where(['ps2.stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP]);
                            },
                            'products' => function ($p) use ($language) {
                                $p->from(['p' => 'plm_doc_item_products'])->select(['p.id', 'p.product_id', 'p.document_item_id'])->with([
                                    'repaired' => function ($r) use ($language) {
                                        $r->from(['r' => 'plm_doc_item_defects'])->select([
                                            'r.defect_id as value', "d.name_{$language} as label", 'r.qty as count', 'r.doc_item_product_id'
                                        ])->leftJoin('defects d', 'r.defect_id = d.id')
                                            ->where(['r.type' => \app\models\BaseModel::DEFECT_REPAIRED]);
                                    },
                                    'scrapped' => function ($r) use ($language) {
                                        $r->from(['s' => 'plm_doc_item_defects'])->select([
                                            's.defect_id as value', "d.name_{$language} as label", 's.qty as count', 's.doc_item_product_id'
                                        ])->leftJoin('defects d', 's.defect_id = d.id')
                                            ->where(['s.type' => \app\models\BaseModel::DEFECT_SCRAPPED]);
                                    },
                                ]);
                            },
                            'equipmentGroup' => function ($eg) {
                                $eg->from(['eg' => 'equipment_group'])->select(['eg.id'])->with([
                                    'equipments' => function ($e) {
                                        return $e->from(['ere' => 'equipment_group_relation_equipment'])
                                            ->select(['e.id as value', 'e.name as label', 'ere.equipment_group_id', 'ere.equipment_id'])
                                            ->leftJoin('equipments e', 'ere.equipment_id = e.id')
                                            ->orderBy(['ere.work_order' => SORT_ASC]);
                                    }
                                ]);
                            }
                        ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id');
                },
            ])->leftJoin('hr_departments hd', 'pd.hr_department_id = hd.id')
            ->leftJoin('shifts sh', 'pd.shift_id = sh.id')
            ->where(['!=', 'pd.status_id', \app\models\BaseModel::STATUS_INACTIVE])
            ->orderBy(["pd.id" => SORT_DESC])
            ->asArray();

        return new ActiveDataProvider([
            'query' => $plm_document,
            'pagination' => [
                'pageSize' => $pageSize,
                'pageSizeParam' => $pageSize,
                'defaultPageSize' => $pageSize,
                'page' => $params['page'] ?? 0
            ]
        ]);
    }

    /**
     * @param $post
     * @return array
     */
    public static function saveStops($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            $stop = $post["stops"];
            $documentItem = $post["plm_document_items"];
            $document = $post["plm_document"];

            if (!empty($stop)) {
                if ($stop["id"]) {
                    $plmStop = PlmStops::findOne(["id" => $stop["id"]]);
                } else {
                    if (!$documentItem["id"]) {
                        if (!$document["id"]) {
                            $doc = new PlmDocuments();
                            $doc->setAttributes([
                                'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                                'hr_department_id' => $document['hr_department_id'],
                                'organisation_id' => $document['organisation_id'],
                                'shift_id' => $document['shift_id'],
                                'add_info' => $document['add_info'],
                                'status_id' => BaseModel::STATUS_ACTIVE
                            ]);
                            if (!$doc->save()) {
                                $response = [
                                    'status' => false,
                                    'errors' => $doc->getErrors(),
                                    'message' => Yii::t('app', 'Document not saved'),
                                ];
                            } else {
                                $document["id"] = $doc->id;
                            }
                        }

                        if ($response["status"]) {
                            $docItem = new PlmDocumentItems();
                            $docItem->setAttributes([
                                'document_id' => $document["id"],
                                'processing_time_id' => $processing->id ?? "",
                                'equipment_group_id' => $documentItem['equipmentGroup']['value'] ?? "",
                            ]);
                            if (!$docItem->save()) {
                                $response = [
                                    'status' => false,
                                    'errors' => $doc->getErrors(),
                                    'message' => Yii::t('app', 'Document not saved'),
                                ];
                            } else {
                                $documentItem["id"] = $docItem->id;
                            }
                        }
                    }
                    $plmStop = new PlmStops();
                }

                $plmStop->setAttributes($stop);
                $plmStop->document_item_id = $documentItem["id"] ?? "";
                $plmStop->stopping_type = PlmStops::getStoppingType($post["type"]);
                $plmStop->status_id = BaseModel::STATUS_ACTIVE;
                if (!$plmStop->save()) {
                    $response = [
                        'status' => false,
                        'errors' => $plmStop->getErrors(),
                        'message' => Yii::t('app', 'Stops data not saved'),
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];
            }
            if ($response['status']) {
                $response["stop_id"] = $plmStop->id;
                $response["reason_name"] = $plmStop->reasons[sprintf("name_%s", Yii::$app->language)];
                $response["document_id"] = $document["id"] ?? "";
                $response["document_item_id"] = $documentItem["id"] ?? "";
                $response["format_begin_date"] = $plmStop->begin_date ? date('d.m.Y H:i:s', strtotime($plmStop->begin_date)) : "";
                $response["format_end_time"] = $plmStop->end_time ? date('d.m.Y H:i:s', strtotime($plmStop->end_time)) : "";
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function deleteStops($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if (!empty($post)) {
                $stop = PlmStops::findOne(["id" => $post["id"]]);
                if (!empty($stop)) {
                    $stop->status_id = BaseModel::STATUS_INACTIVE;
                    if (!$stop->save()) {
                        $response = [
                            'status' => false,
                            'errors' => $stop->getErrors(),
                            'message' => Yii::t('app', 'Stop data not saved'),
                        ];
                    }
                } else {
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'Stop data not found'),
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];
            }
            if ($response['status']) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }
}