<?php

namespace app\modules\hr\models;

use app\components\OurCustomBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class BaseModel
 * @package app\modules\references\models
 */
class BaseModel extends ActiveRecord
{

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
}
