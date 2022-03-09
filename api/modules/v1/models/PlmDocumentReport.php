<?php


namespace app\api\modules\v1\models;


use app\models\BaseModel;
use app\modules\plm\models\PlmDocItemEquipments;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmStops;
use Yii;
use yii\data\ActiveDataProvider;

class PlmDocumentReport implements PlmDocumentReportInterface
{

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function getData($params = []): ActiveDataProvider
    {
        $pageSize = $params['page_size'] ?? 20;
        $data = PlmDocumentItems::find()
            ->alias('pdi')
            ->select([
                "pdi.*",
                "to_char(pd.reg_date, 'DD.MM.YYYY HH24:MI:SS') as format_reg_date",
                "o.name as organisation_name",
                "hd.name as department_name",
                "CONCAT(sh.name, ' (', sh.start_time , '-', sh.end_time, ')') as shift_name",
                "to_char(ppt.begin_date, 'DD.MM.YYYY HH24:MI:SS') as begin_date",
                "to_char(ppt.end_date, 'DD.MM.YYYY HH24:MI:SS') as end_date",
                "pdie.equipment",
                "MAX(psp.plan_stop_date) as plan_stop_date",
                "MAX(psup.unplan_stop_date) as unplan_stop_date",
                "EXTRACT(EPOCH FROM (ppt.end_date - ppt.begin_date)) AS plan_date"
            ])
            ->with([
                'products' => function ($p){
                    $p->from(['pdip' => 'plm_doc_item_products'])
                        ->select([
                            "pdip.id",
                            "pdip.product_id",
                            "pdip.document_item_id",
                            "pdip.qty",
                            "pdip.fact_qty",
                            "p.name as product_name"
                        ])
                        ->leftJoin(["p" => "products"], "pdip.product_id = p.id")
                    ;
                },
            ])
            ->leftJoin(["pd" => "plm_documents"], 'pdi.document_id = pd.id')
            ->leftJoin(["psp" => PlmStops::find()
                ->select([
                    "MAX(document_item_id) as document_item_id",
                    "SUM(EXTRACT(EPOCH FROM (end_time - begin_date))) AS plan_stop_date"
                ])
                ->where([
                    'stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP,
                    "status_id" => BaseModel::STATUS_ACTIVE
                ])
                ->groupBy(["document_item_id"])
            ],'pdi.id = psp.document_item_id')
            ->leftJoin(["psup" => PlmStops::find()
                ->select([
                    "MAX(document_item_id) as document_item_id",
                    "SUM(EXTRACT(EPOCH FROM (end_time - begin_date))) AS unplan_stop_date"
                ])
                ->where([
                    'stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP,
                    "status_id" => BaseModel::STATUS_ACTIVE
                ])
                ->groupBy(["document_item_id"])
            ],'pdi.id = psup.document_item_id')
            ->leftJoin('plm_processing_time ppt', 'pdi.processing_time_id = ppt.id')
            ->leftJoin(["pdie" => PlmDocItemEquipments::find()
                ->alias("pdie")
                ->select([
                    "pdie.document_item_id",
                    "STRING_AGG(DISTINCT e.name,', ') AS equipment",
                ])
                ->leftJoin(["e" => "equipments"], "e.id = pdie.equipment_id")
                ->groupBy(["pdie.document_item_id"])
            ], "pdi.id = pdie.document_item_id")
            ->leftJoin('hr_departments hd', 'pd.hr_department_id = hd.id')
            ->leftJoin('hr_departments o', 'pd.organisation_id = o.id')
            ->leftJoin('shifts sh', 'pd.shift_id = sh.id')
            ->groupBy([
                "pdi.id", "pd.id",
                "ppt.begin_date", "ppt.end_date",
                "pd.reg_date", "o.name",
                "hd.name", "sh.name",
                "sh.start_time", "sh.end_time",
                "pdie.equipment",
            ])
            ->where(['!=', 'pd.status_id', \app\models\BaseModel::STATUS_INACTIVE])
            ->andWhere(["pdi.status_id" => BaseModel::STATUS_ACTIVE])
            ->orderBy(["pd.id" => SORT_DESC])
            ->asArray();
            return new ActiveDataProvider([
                'query' => $data,
                'pagination' => [
                    'pageSize' => $pageSize,
                    'pageSizeParam' => $pageSize,
                    'defaultPageSize' => $pageSize,
                    'page' => $params['page'] ?? 0
                ]
            ]);
    }
}