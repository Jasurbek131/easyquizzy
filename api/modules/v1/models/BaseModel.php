<?php

namespace app\api\modules\v1\models;

use app\modules\plm\models\PlmDocuments;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "plm_documents".
 */
class BaseModel extends PlmDocuments
{

    public static function getDocumentElements($id) {
        $language = Yii::$app->language;
        return PlmDocuments::find()->select([
            'id', 'doc_number', 'reg_date', 'hr_department_id', 'add_info', 'shift_id', 'organisation_id'
        ])->with([
            'plm_document_items' => function($q) use ($language) {
                $q->from(['pdi' => 'plm_document_items'])
                    ->select(['pdi.*', 'ppt.begin_date as start_work', 'ppt.end_date as end_work'])->with([
                        'products' => function($p) use ($language) {
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
                                'repaired' => function($r) use ($language) {
                                    $r->from(['r' => 'plm_doc_item_defects'])->select([
                                        'r.defect_id as value', "d.name_{$language} as label", 'r.qty as count', 'r.doc_item_product_id'
                                    ])->leftJoin('defects d', 'r.defect_id = d.id')
                                        ->where(['r.type' => \app\models\BaseModel::DEFECT_REPAIRED]);
                                },
                                'scrapped' => function($r) use ($language) {
                                    $r->from(['s' => 'plm_doc_item_defects'])->select([
                                        's.defect_id as value', "d.name_{$language} as label", 's.qty as count', 's.doc_item_product_id'
                                    ])->leftJoin('defects d', 's.defect_id = d.id')
                                        ->where(['s.type' => \app\models\BaseModel::DEFECT_SCRAPPED]);
                                },
                            ]);
                        },
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
                        'equipmentGroup' => function($eg) {
                            $eg->from(['eg' => 'equipment_group'])->select(['eg.id', 'eg.id as value'])->with([
                                'equipments' => function($e) {
                                    $e->from(['ere' => 'equipment_group_relation_equipment'])
                                        ->select(['e.id as value', 'e.name as label', 'ere.equipment_group_id', 'ere.equipment_id'])
                                        ->leftJoin('equipments e', 'ere.equipment_id = e.id')
                                        ->orderBy(['ere.work_order' => SORT_ASC]);
                                },
                                'productLifecycles' => function($p) {
                                    $p->from(['pl' => 'product_lifecycle'])->select([
                                        'p.id as value', 'p.name as label', 'pl.product_id', 'pl.equipment_group_id'
                                    ])->leftJoin('products p', 'pl.product_id = p.id');
                                }
                            ]);
                        }
                    ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id');
            },
            'hrDepartments' => function($hd) {
                $hd->from(['hd' => 'hr_departments'])->select([
                    'hd.id as value', 'hd.name as label'
                ]);
            },
            'shifts' => function($sh) {
                $sh->from(['sh' => 'shifts'])->select([
                   'sh.id as value', 'sh.name as label'
                ]);
            },
        ])->where(['id' => (integer)$id])->asArray()->limit(1)->one();
    }

    public static function getPlmDocuments($params) {
        $pageSize = $params['page_size'] ?? 20;
        $language = Yii::$app->language;
        $plm_document = PlmDocuments::find()->with([
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
                        'products' => function($p) use ($language) {
                            $p->from(['p' => 'plm_doc_item_products'])->select(['p.id', 'p.product_id', 'p.document_item_id'])->with([
                                'repaired' => function($r) use ($language) {
                                    $r->from(['r' => 'plm_doc_item_defects'])->select([
                                        'r.defect_id as value', "d.name_{$language} as label", 'r.qty as count', 'r.doc_item_product_id'
                                    ])->leftJoin('defects d', 'r.defect_id = d.id')
                                        ->where(['r.type' => \app\models\BaseModel::DEFECT_REPAIRED]);
                                },
                                'scrapped' => function($r) use ($language) {
                                    $r->from(['s' => 'plm_doc_item_defects'])->select([
                                        's.defect_id as value', "d.name_{$language} as label", 's.qty as count', 's.doc_item_product_id'
                                    ])->leftJoin('defects d', 's.defect_id = d.id')
                                        ->where(['s.type' => \app\models\BaseModel::DEFECT_SCRAPPED]);
                                },
                            ]);
                        },
                        'equipmentGroup' => function($eg) {
                            $eg->from(['eg' => 'equipment_group'])->select(['eg.id'])->with([
                                'equipments' => function($e) {
                                    return $e->from(['ere' => 'equipment_group_relation_equipment'])
                                        ->select(['e.id as value', 'e.name as label', 'ere.equipment_group_id', 'ere.equipment_id'])
                                        ->leftJoin('equipments e', 'ere.equipment_id = e.id')
                                        ->orderBy(['ere.work_order' => SORT_ASC]);
                                }
                            ]);
                        }
                    ])->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id');
            },
        ])->where(['!=', 'status_id', \app\models\BaseModel::STATUS_INACTIVE])
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
