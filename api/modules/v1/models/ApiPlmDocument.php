<?php


namespace app\api\modules\v1\models;


use app\models\BaseModel;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocItemEquipments;
use app\modules\plm\models\PlmDocItemProducts;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmDocuments;
use app\modules\plm\models\PlmProcessingTime;
use app\modules\plm\models\PlmStops;
use Yii;
use yii\data\ActiveDataProvider;

class ApiPlmDocument extends PlmDocuments
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
                    $plannedStopped = $item['planned_stopped'];
                    $unplannedStopped = $item['unplanned_stopped'];

                    /**
                     * Planned stop
                     */
                    if (!empty($plannedStopped)) {
                        $planStop = new PlmStops();
                        if ($item['planned_stop_id'])
                            $planStop = PlmStops::findOne($item['planned_stop_id']);
                        $planStop->setAttributes([
                            'reason_id' => $plannedStopped['reason_id'],
                            'begin_date' => date('Y-m-d H:i', strtotime($plannedStopped['begin_date'])),
                            'end_time' => date('Y-m-d H:i', strtotime($plannedStopped['end_time'])),
                            'add_info' => $plannedStopped['add_info'],
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
                            break;
                        }
                    }

                    /**
                     * Un planned stop
                     */
                    if (!empty($unplannedStopped) && $response['status']) {
                        $unPlanStop = new PlmStops();
                        if ($item['unplanned_stop_id'])
                            $unPlanStop = PlmStops::findOne($item['unplanned_stop_id']);
                        $unPlanStop->setAttributes([
                            'reason_id' => $unplannedStopped['reason_id'],
                            'begin_date' => date('Y-m-d H:i', strtotime($unplannedStopped['begin_date'])),
                            'end_time' => date('Y-m-d H:i', strtotime($unplannedStopped['end_time'])),
                            'bypass' => $unplannedStopped['bypass'],
                            'add_info' => $unplannedStopped['add_info'],
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
                            break;
                        }
                    }

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
                                    'product_lifecycle_id' => $product['product_lifecycle_id'],
                                    'qty' => $product['qty'],
                                    'fact_qty' => $product['fact_qty']
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
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getDocumentElements($id)
    {
        $language = Yii::$app->language;
        return PlmDocuments::find()
            ->select([
                'id', 'doc_number', 'reg_date', 'hr_department_id', 'add_info', 'shift_id', 'organisation_id'
            ])->with([
                'plm_document_items' => function ($q) use ($language) {
                    $q->from(['pdi' => 'plm_document_items'])
                        ->select(['pdi.*', 'ppt.begin_date as start_work', 'ppt.end_date as end_work'])->with([
                            'products' => function ($p) use ($language) {
                                $p->from(['p' => 'plm_doc_item_products'])->select([
                                    'p.id',
                                    'p.product_lifecycle_id',
                                    'pl.lifecycle',
                                    'pl.bypass',
                                    'p.product_id',
                                    'p.product_id as value',
                                    'p.qty',
                                    'p.fact_qty',
                                    'p.document_item_id'
                                ])->leftJoin('product_lifecycle pl', 'p.product_lifecycle_id = pl.id')
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
                                        'lifecycles' => function ($pl) {
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
}