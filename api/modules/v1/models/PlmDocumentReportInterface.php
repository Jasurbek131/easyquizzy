<?php


namespace app\api\modules\v1\models;

interface PlmDocumentReportInterface
{
    /**
     * @param $params
     * @return array
     */
    public static function getData($params = []): array;
}