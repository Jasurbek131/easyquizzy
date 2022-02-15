<?php

namespace app\models;

use app\components\OurCustomBehavior;
use app\widgets\Language;
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
    const STATUS_DELETE             = 0;
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;


    // defect type
    const DEFECT_REPAIRED = 1; // Tamirlanadigan
    const DEFECT_SCRAPPED = 2; // Yaroqsiz

    // category type
    const CATEGORY_PLANNED = 1;
    const CATEGORY_UNPLANNED = 2;

    const TYPE_EQUIPMENT_GROUP_STATSIONAR = 1;
    const TYPE_EQUIPMENT_GROUP_KONVEYER = 2;
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public static function getStatusList($key = null, $isArray = false) {
        $language = Language::widget();
        if (!is_null($key)) {
            $status = StatusList::findOne(['id' => $key]);
            if (!empty($status)) {
                if ($status['id'] == self::STATUS_INACTIVE) {
                    return "<span class='badge badge-danger d-block'>".$status[$language]."</span>";
                }
                return "<span class='badge badge-success d-block'>".$status[$language]."</span>";
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
}
