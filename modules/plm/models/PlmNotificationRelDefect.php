<?php

namespace app\modules\plm\models;

use app\modules\references\models\Defects;
use Yii;

/**
 * This is the model class for table "plm_notification_rel_defect".
 *
 * @property int $id
 * @property int $plm_notification_list_id
 * @property int $defect_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Defects $defects
 * @property PlmNotificationsList $plmNotificationsList
 */
class PlmNotificationRelDefect extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_notification_rel_defect';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plm_notification_list_id', 'defect_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['plm_notification_list_id', 'defect_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['defect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['defect_id' => 'id']],
            [['plm_notification_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmNotificationsList::className(), 'targetAttribute' => ['plm_notification_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plm_notification_list_id' => Yii::t('app', 'Plm Notification List ID'),
            'defect_id' => Yii::t('app', 'Defect ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefects()
    {
        return $this->hasOne(Defects::className(), ['id' => 'defect_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmNotificationsList()
    {
        return $this->hasOne(PlmNotificationsList::className(), ['id' => 'plm_notification_list_id']);
    }
}
