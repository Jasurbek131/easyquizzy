<?php


namespace app\api\modules\v1\models;

use yii\data\ActiveDataProvider;

interface PlmDocumentReportInterface
{

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function getData($params = []): ActiveDataProvider;
}