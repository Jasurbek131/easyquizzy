<?php


namespace app\api\modules\v1\models;


use yii\data\ActiveDataProvider;

interface PlmStopReportInterface
{

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public static function getStopData($params):ActiveDataProvider;
}