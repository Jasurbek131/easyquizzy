<?php


namespace app\api\modules\v1\models;


use app\modules\plm\models\PlmDocuments;
use Yii;

class PlmDocumentReport implements PlmDocumentReportInterface
{
    /**
     * @param $params
     * @return array
     */
    public static function getData($params = []): array
    {
//        $pageSize = $params['page_size'] ?? 20;
        $response = ['status' => false];

        $language = Yii::$app->language;
        $data = PlmDocuments::find()
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
            ->asArray()
            ->all();
        if (!empty($data)){
            $response = [
                'status' => true,
                "items" => $data,
            ];
        }

        return $response;
//        return new ActiveDataProvider([
//            'query' => $plm_document,
//            'pagination' => [
//                'pageSize' => $pageSize,
//                'pageSizeParam' => $pageSize,
//                'defaultPageSize' => $pageSize,
//                'page' => $params['page'] ?? 0
//            ]
//        ]);
    }
}