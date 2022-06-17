<?php

namespace app\models;

use app\components\OurCustomBehavior;
use app\widgets\Language;
use DateTime;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BaseModel
 * @package app\modules\toquv\models
 */
class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;
    const STATUS_ACCEPTED           = 4;
    const STATUS_REJECTED           = 5;

    /**
     * Nuqsonlar turi
     */
    const DEFECT_REPAIRED = 1; // Tamirlanadigan
    const DEFECT_SCRAPPED = 2; // Yaroqsiz

    /**
     * Uskunalar guruhi turi uchun
     */
    const TYPE_EQUIPMENT_GROUP_STATSIONAR = 1;
    const TYPE_EQUIPMENT_GROUP_KONVEYER = 2;

    /**
     * Rollar turi
     */
    const CREATE = 1;
    const UPDATE = 2;
    const VIEW = 3;
    const DELETE = 4;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public static function getStatusList($key = null, $isArray = false)
    {
        $language = Language::widget();
        if (!is_null($key)) {
            $status = StatusList::findOne(['id' => $key]);
            if (!empty($status)) {
                if ($status['id'] == self::STATUS_INACTIVE) {
                    return "<span class='badge badge-danger d-block'>" . $status[$language] . "</span>";
                }
                return "<span class='badge badge-success d-block'>" . $status[$language] . "</span>";
            }
            return "";
        }

        $list = StatusList::find()->asArray()->select(['id as value', "{$language} as label"])->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @return array
     */
    public static function getEquipmentGroupTypeList(): array
    {
        return [
            [
                'value' => self::TYPE_EQUIPMENT_GROUP_STATSIONAR,
                'label' => "Statsionar"
            ],
            [
                'value' => self::TYPE_EQUIPMENT_GROUP_KONVEYER,
                'label' => "Konveyer"
            ],
        ];
    }

    /**
     * @param $time1
     * @param $time2
     * @param string $need
     * @return float|int
     */
    public static function getDiffDateTime($time1, $time2, $need = "s")
    {
        try {
            $time1 = new DateTime($time1);
            $time2 = new DateTime($time2);
            $diff = $time1->diff($time2);
            $total = 0;
            switch ($need) {
                case "y":
                    $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                    break;
                case "m":
                    $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                    break;
                case "d":
                    $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                    break;
                case "h":
                    $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                    break;
                case "i":
                    $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                    break;
                case "s":
                    $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                    break;
            }
            if ($diff->invert)
                return -1 * $total;
            else
                return $total;

        } catch (Exception $e) {

        }
    }
}
