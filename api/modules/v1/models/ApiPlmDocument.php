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
use app\modules\references\models\Categories;
use app\modules\references\models\Defects;
use app\modules\references\models\EquipmentGroup;
use app\widgets\Language;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

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
            if (isset($document["id"]) && !empty($document['id']))
                $doc = PlmDocuments::findOne($document['id']);
            $doc->setAttributes([
                'reg_date' => date("Y-m-d H:i:s", strtotime($document['reg_date'])),
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

                    if ($response['status'] && $item['start_work'] && $item['end_work']) {
                        /*** Ishlagan vaqti ***/
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

                    /*** Hujjat qismlarini yaratish ***/
                    $docItem = new PlmDocumentItems();
                    if ($item['id'])
                        $docItem = PlmDocumentItems::findOne($item['id']);
                    $docItem->setAttributes([
                        'document_id' => $doc->id,
                        'processing_time_id' => $processing->id ?? "",
                        'equipment_group_id' => $item['equipmentGroup']['value'] ?? "",
                        'lifecycle' => $item['lifecycle'],
                        'bypass' => $item['bypass'],
                        'target_qty' => $item['target_qty'],
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

                    /*** ishlagan vaqti uchun xabar yuborish qismi ***/
                    $notificationTime = PlmNotificationsList::existsNotification([
                        "plm_doc_item_id" => $docItem->id,
                        "category_id" => Categories::getIdByToken(Categories::TOKEN_WORKING_TIME),
                    ]);
                    if (!$notificationTime || $notificationTime->status_id == BaseModel::STATUS_REJECTED) // Oldin yozilmagan yoki qaytarilgan bo'lsa yangi yozadi
                        $notificationTime = new PlmNotificationsList();
                    if ($notificationTime->status_id !== BaseModel::STATUS_ACCEPTED){
                        $notificationTime->setAttributes([
                            "plm_doc_item_id" => $docItem->id,
                            "begin_time" => date("Y-m-d H:i:s", strtotime($item['start_work'])),
                            "end_time" => date("Y-m-d H:i:s", strtotime($item['end_work'])),
                            "status_id" => BaseModel::STATUS_ACTIVE,
                            "category_id" => Categories::getIdByToken(Categories::TOKEN_WORKING_TIME),
                        ]);
                        if (!$notificationTime->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $notificationTime->getErrors(),
                                'message' => Yii::t('app', 'Doc item time notification not saved'),
                            ];
                            break;
                        }
                    }

                    /*** Rejali toxatalishlar ***/
                    $plannedStops = $item['planned_stops'];
                    if (!empty($plannedStops)) {
                        foreach ($plannedStops as $stop) {
                            $planStop = new PlmStops();
                            if ($stop['id'])
                                $planStop = PlmStops::findOne($stop['id']);
                            $planStop->setAttributes([
                                'document_item_id' => $docItem->id,
                                'category_id' => $stop['category_id'],
                                'begin_date' => date('Y-m-d H:i', strtotime($stop['begin_date'])),
                                'end_time' => date('Y-m-d H:i', strtotime($stop['end_time'])),
                                'add_info' => $stop['add_info'],
                                'status_id' => BaseModel::STATUS_ACTIVE,
                                'stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP
                            ]);
                            if (!$planStop->save()) {
                                $response = [
                                    'status' => false,
                                    'line' => __LINE__,
                                    'errors' => $planStop->getErrors(),
                                    'message' => Yii::t('app', 'Planned stop not saved'),
                                ];
                                break 2;
                            }
                        }
                    }

                    /*** Rejasiz toxatalishlar ***/
                    $unplannedStops = $item['unplanned_stops'];
                    if (!empty($unplannedStops)) {
                        foreach ($unplannedStops as $unStop) {
                            $unPlanStop = new PlmStops();
                            if ($unStop['id'])
                                $unPlanStop = PlmStops::findOne($unStop['id']);
                            $unPlanStop->setAttributes([
                                'document_item_id' => $docItem->id,
                                'category_id' => $unStop['category_id'],
                                'begin_date' => date('Y-m-d H:i', strtotime($unStop['begin_date'])),
                                'end_time' => date('Y-m-d H:i', strtotime($unStop['end_time'])),
                                'bypass' => $unStop['bypass'],
                                'add_info' => $unStop['add_info'],
                                'status_id' => BaseModel::STATUS_ACTIVE,
                                'stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP
                            ]);
                            if (!$unPlanStop->save()) {
                                $response = [
                                    'status' => false,
                                    'line' => __LINE__,
                                    'errors' => $unPlanStop->getErrors(),
                                    'message' => Yii::t('app', 'Unplanned stop not saved'),
                                ];
                                break 2;
                            }

                            $notificationUnplannedStop = PlmNotificationsList::existsNotification([
                                "plm_doc_item_id" => $docItem->id,
                                "category_id" => $unStop['category_id'],
                                "stop_id" => $unPlanStop->id,
                            ]);

                            if (!$notificationUnplannedStop || $notificationUnplannedStop->status_id == BaseModel::STATUS_REJECTED)
                                $notificationUnplannedStop = new PlmNotificationsList();

                            if ($notificationUnplannedStop->status_id !== BaseModel::STATUS_ACCEPTED) {
                                $notificationUnplannedStop->setAttributes([
                                    "plm_doc_item_id" => $docItem->id,
                                    'status_id' => BaseModel::STATUS_ACTIVE,
                                    'category_id' => $unStop['category_id'],
                                    'stop_id' => $unPlanStop->id,
                                    "begin_time" => $unPlanStop->begin_date,
                                    "end_time" => $unPlanStop->end_time,
                                    "by_pass" => $unPlanStop->bypass,
                                ]);
                                if (!$notificationUnplannedStop->save()) {
                                    $response = [
                                        'status' => false,
                                        'line' => __LINE__,
                                        'errors' => $notificationUnplannedStop->getErrors(),
                                        'message' => Yii::t('app', 'Doc item unplanned notification not saved'),
                                    ];
                                    break 2;
                                }
                            }
                        }
                    }

                    /*** Tamirlangan mahsultolar uchun xabar yuborish ***/
                    $isCreateRepaired = false;
                    $notificationRepaired = PlmNotificationsList::existsNotification([
                        "plm_doc_item_id" => $docItem->id,
                        "category_id" => Categories::getIdByToken(Categories::TOKEN_REPAIRED)
                    ]);
                    if ((!$notificationRepaired || $notificationRepaired->status_id == BaseModel::STATUS_REJECTED))
                        $isCreateRepaired = true;
                    if (isset($notificationRepaired->id) && !empty($notificationRepaired->id) && $notificationRepaired->status_id == BaseModel::STATUS_ACTIVE)
                        PlmNotificationRelDefect::deleteAll(["plm_notification_list_id" => $notificationRepaired->id]);

                    /*** Yaroqsiz mahsultolar uchun xabar yuborish ***/
                    $isCreateScrapped = false;
                    $notificationScrapped = PlmNotificationsList::existsNotification([
                        "plm_doc_item_id" => $docItem->id,
                        "category_id" => Categories::getIdByToken(Categories::TOKEN_SCRAPPED)
                    ]);
                    if ((!$notificationScrapped || $notificationScrapped->status_id == BaseModel::STATUS_REJECTED))
                        $isCreateScrapped = true;
                    if (isset($notificationScrapped->id) && !empty($notificationScrapped->id) && $notificationScrapped->status_id == BaseModel::STATUS_ACTIVE)
                        PlmNotificationRelDefect::deleteAll(["plm_notification_list_id" => $notificationScrapped->id]);

                    /*** Mahsultolar ***/
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

                            if ($newProductItem->id)
                                PlmDocItemDefects::deleteAll(["doc_item_product_id" => $newProductItem->id]);

                            $repaired = $product['repaired'] ?? [];
                            foreach ($repaired as $repair) {
                                if ($repair['count']) {

                                    if ($isCreateRepaired){
                                        $notificationRepaired = new PlmNotificationsList([
                                            "plm_doc_item_id" => $docItem->id,
                                            'status_id' => BaseModel::STATUS_ACTIVE,
                                            "category_id" => Categories::getIdByToken(Categories::TOKEN_REPAIRED),
                                        ]);
                                        if (!$notificationRepaired->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $notificationRepaired->getErrors(),
                                                'message' => Yii::t('app', 'Doc item repaired notification not saved'),
                                            ];
                                            break 3;
                                        }
                                        $isCreateRepaired = false;
                                    }

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

                                    $plmNotificationRelDefectRepaired = new PlmNotificationRelDefect([
                                        'plm_notification_list_id' => $notificationRepaired->id,
                                        'defect_id' => $repair['value'],
                                        'status_id' => BaseModel::STATUS_ACTIVE,
                                        'defect_count' => $repair['count'],
                                    ]);
                                    if (!$plmNotificationRelDefectRepaired->save()) {
                                        $response = [
                                            'status' => false,
                                            'line' => __LINE__,
                                            'errors' => $plmNotificationRelDefectRepaired->getErrors(),
                                            'message' => Yii::t('app', 'Plm notification rel defect repaired not saved'),
                                        ];
                                        break 3;
                                    }
                                }
                            }

                            $scrapped = $product['scrapped'] ?? [];
                            foreach ($scrapped as $scrap) {
                                if ($scrap['count']) {
                                    if ($isCreateScrapped){
                                        $notificationScrapped = new PlmNotificationsList([
                                            "plm_doc_item_id" => $docItem->id,
                                            'status_id' => BaseModel::STATUS_ACTIVE,
                                            "category_id" => Categories::getIdByToken(Categories::TOKEN_SCRAPPED),
                                        ]);
                                        if (!$notificationScrapped->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $notificationScrapped->getErrors(),
                                                'message' => Yii::t('app', 'Doc item scrapped notification not saved'),
                                            ];
                                            break 3;
                                        }
                                        $isCreateScrapped = false;
                                    }

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

                                    $plmNotificationRelDefectScrapped = new PlmNotificationRelDefect([
                                        'plm_notification_list_id' => $notificationScrapped->id,
                                        'defect_id' => $scrap['value'],
                                        'status_id' => BaseModel::STATUS_ACTIVE,
                                        'defect_count' => $scrap['count'],
                                    ]);
                                    if (!$plmNotificationRelDefectScrapped->save()) {
                                        $response = [
                                            'status' => false,
                                            'line' => __LINE__,
                                            'errors' => $plmNotificationRelDefectScrapped->getErrors(),
                                            'message' => Yii::t('app', 'Plm notification rel defect scrapped not saved'),
                                        ];
                                        break 3;
                                    }
                                }
                            }
                        }
                    }

                    /*** Uskunalar ***/
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

            if ($response['status']) {
                $response["doc_item_id"] = $docItem->id ?? "";
                $response["doc_id"] = $doc->id ?? "";
                $response["additional"] = PlmDocumentItems::getStops($docItem->id);
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
                    $docItem->status_id = BaseModel::STATUS_INACTIVE;
                    if (!$docItem->save())
                        $response = [
                            'status' => false,
                            'errors' => $docItem->getErrors(),
                            'message' => Yii::t('app', 'Doc item not deleted'),
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
                                        'ps1.category_id',
                                        "ps1.document_item_id",
                                        "c.name_{$language} as category_name",
                                    ])
                                    ->leftJoin(["c" => "categories"], "ps1.category_id = c.id")
                                    ->where([
                                        'ps1.stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP,
                                        'ps1.status_id' => BaseModel::STATUS_ACTIVE,
                                    ]);
                            },
                            'unplanned_stops' => function ($e) use ($language) {
                                $e->from(['ps2' => 'plm_stops'])
                                    ->select([
                                        'ps2.id',
                                        'ps2.begin_date',
                                        'ps2.end_time',
                                        'ps2.add_info',
                                        'ps2.category_id',
                                        'ps2.bypass',
                                        "ps2.document_item_id",
                                        "c.name_{$language} as category_name",
                                    ])
                                    ->leftJoin(["c" => "categories"], "ps2.category_id = c.id")
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
                            'notifications_status' => function($ns){
                                $ns->from(['pnl' => "plm_notifications_list"])
                                    ->select([
                                        "pnl.id",
                                        "pnl.plm_doc_item_id",
                                        "pnl.status_id",
                                        "pnl.category_id",
                                        "pnl.stop_id",
                                        "c.token",
                                    ])
                                    ->joinWith(["messages"])
                                    ->leftJoin(["c" => "categories"], 'pnl.category_id = c.id');
//                                    ->where(["NOT IN", "pnl.status_id" , [\app\modules\plm\models\BaseModel::STATUS_REJECTED]]);
                            }
                        ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id')
                        ->where(["pdi.status_id" => BaseModel::STATUS_ACTIVE]);
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

                $data["plm_document_items"][$key]["equipmentGroup"] = is_array($item["equipmentGroup"]) && count($item["equipmentGroup"]) > 0 ?
                    EquipmentGroup::getProductList([$item["equipmentGroup"]], $item["equipmentGroup"], 0) : [
                        "product_list" => []
                    ];
                unset($data["plm_document_items"][$key]["equipmentGroup"]["cycles"]);

                $data["plm_document_items"][$key]["notifications_status"] = PlmNotificationsList::formatterNotificationStatus($item["notifications_status"]);
            }
        }
        return $data ?? [];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public static function getPlmDocuments($params = [])
    {
        $pageSize = $params['page_size'] ?? 20;
        $language = Yii::$app->language;
        $plm_document = PlmDocuments::find()
            ->alias('pd')
            ->select([
                "pd.*",
                "to_char(pd.reg_date, 'DD.MM.YYYY HH24:MI:SS') as format_reg_date",
                "sh.name as shift",
                'hd.name as department'
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
    public static function deleteStops($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if (!empty($post)) {
                $plmNotification = PlmNotificationsList::findOne(['stop_id' => $post["id"]]);
                if (!empty($plmNotification)) {
                    $plmNotification->status_id = BaseModel::STATUS_INACTIVE;
                    if (!$plmNotification->save()) {
                        $response = [
                            'status' => false,
                            'errors' => $plmNotification->getErrors(),
                            'message' => Yii::t('app', 'Notification not deleted'),
                        ];
                    }
                }

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