<?php


namespace app\api\modules\v1\models;


use app\modules\plm\models\PlmDocumentItems;
use Yii;

class PlmDocumentReport
{
    public static function documentData()
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        $response["items"] = PlmDocumentItems::find()
            ->alias("pdi")
            ->select([
                "pdi.id",
                "pd.reg_date",
                "pd.doc_number",
                "pd.add_info",
                "hd.name as department_name",
                "sh.name as shift_name",
            ])
            ->joinWith(["products.product"])
            ->leftJoin(["pd" => "plm_documents"], "pdi.document_id = pd.id")
            ->leftJoin(["hd" => "hr_departments"], "pd.hr_department_id = hd.id")
            ->leftJoin(["sh" => "shifts"], "pd.shift_id = sh.id")
            ->asArray()
            ->all();

        if (empty($response["items"]))
            $response = [
                "status" => false,
                "message" => Yii::t('app', "Data is empty")
            ];

        return $response;
    }
}