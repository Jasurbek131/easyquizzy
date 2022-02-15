<?php

namespace app\modules\plm\models;

use app\modules\references\models\Reasons;
use Yii;

/**
 * This is the model class for table "plm_stops".
 *
 * @property int $id
 * @property string $begin_date
 * @property string $end_time
 * @property string $add_info
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property int $stopping_type
 * @property int $reason_id
 * @property string $bypass
 * @property Reasons[] $reasons
 * @property PlmDocumentItems[] $plmDocumentItems
 */
class PlmStops extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_stops';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'stopping_type', 'reason_id'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'stopping_type', 'reason_id'], 'integer'],
            [['begin_date', 'end_time'], 'safe'],
            [['add_info'], 'string'],
            [['bypass'], 'string', 'max' => 255],
            [['reason_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reasons::className(), 'targetAttribute' => ['reason_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_time' => Yii::t('app', 'End Time'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'stopping_type' => Yii::t('app', 'Stopping Type'),
            'reason_id' => Yii::t('app', 'Reason ID'),
            'bypass' => Yii::t('app', 'Bypass'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReasons()
    {
        return $this->hasMany(Reasons::className(), ['reason_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasMany(PlmDocumentItems::className(), ['unplanned_stop_id' => 'id']);
    }
}
