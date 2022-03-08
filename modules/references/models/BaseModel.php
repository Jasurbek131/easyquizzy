<?php

namespace app\modules\references\models;

use app\components\OurCustomBehavior;
use app\models\StatusList;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\BaseModel as Bm;

/**
 * Class BaseModel
 * @package app\modules\references\models
 */
class BaseModel extends ActiveRecord
{
    const STATUS_DELETE             = 0;
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;

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

    public static function getStatusList($key = null, $isArray = false) {
        return Bm::getStatusList($key, $isArray);
    }
}
