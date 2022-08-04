<?php

namespace app\api\modules\v1\models;

use app\modules\plm\models\PlmNotificationsList;
use app\modules\plm\models\PlmNotificationsListRelReason;
use app\modules\references\models\Categories;
use yii\data\ActiveDataProvider;

class PlmStopReport implements PlmStopReportInterface
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public static function getStopData($params):ActiveDataProvider
    {
        $pageSize = $params['page_size'] ?? 20;
        $data = PlmNotificationsListRelReason::find()
            ->alias("pnlrr")
            ->select([
                "pnlrr.id",
                "r.name_uz as name",
                "c.name_uz as category_name",
                "to_char(ps.begin_date, 'DD.MM.YYYY HH24:MI:SS') as begin_date",
                "to_char(ps.end_time, 'DD.MM.YYYY HH24:MI:SS') as end_date",
                "EXTRACT(EPOCH FROM (ps.end_time - ps.begin_date)) AS diff_date"
            ])
            ->leftJoin(["pnl" => "plm_notifications_list"], "pnlrr.plm_notification_list_id = pnl.id")
            ->leftJoin(["c" => "categories"], "pnl.category_id = c.id")
            ->leftJoin(["ps" => "plm_stops"], "pnl.stop_id = ps.id")
            ->leftJoin(["r" => "reasons"], "pnlrr.reason_id = r.id")
            ->where([
                "pnl.status_id" => PlmNotificationsList::STATUS_ACCEPTED,
                "c.token" => Categories::TOKEN_UNPLANNED
            ])
            ->andFilterWhere([">=", "ps.begin_date", $params["begin_date"]])
            ->andFilterWhere(["<=", "ps.end_time", $params["end_date"]])
            ->andFilterWhere(["r.id" => $params["stop_id"]])
            ->andFilterWhere(["c.id" => $params["category_id"]])
            ->orderBy(["pnl.id" => SORT_DESC])
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