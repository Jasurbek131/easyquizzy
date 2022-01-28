<?php

namespace app\modules\references\models;

use app\components\OurCustomBehavior;
use app\models\StatusList;
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
        $language = Yii::$app->language;
        if (!is_null($key)) {
            return StatusList::findOne(['id' => $key]);
        } else {
            $list = StatusList::find()->asArray()->select(['id as value', "name_{$language} as label"])->all();
            if ($isArray) {
                return $list;
            }
            return ArrayHelper::map($list, 'value', 'label');
        }
    }
}
