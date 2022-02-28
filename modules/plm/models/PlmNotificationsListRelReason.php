<?php

namespace app\modules\plm\models;

use app\modules\references\models\Reasons;
use Yii;

/**
 * This is the model class for table "plm_notifications_list_rel_reason".
 *
 * @property int $id
 * @property int $plm_notification_list_id
 * @property int $reason_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property PlmNotificationsList $plmNotificationsList
 * @property Reasons $reasons
 */
class PlmNotificationsListRelReason extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_notifications_list_rel_reason';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plm_notification_list_id', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['plm_notification_list_id', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['plm_notification_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmNotificationsList::className(), 'targetAttribute' => ['plm_notification_list_id' => 'id']],
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
            'plm_notification_list_id' => Yii::t('app', 'Plm Notification List ID'),
            'reason_id' => Yii::t('app', 'Reason ID'),
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
    public function getPlmNotificationsList()
    {
        return $this->hasOne(PlmNotificationsList::className(), ['id' => 'plm_notification_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReasons()
    {
        return $this->hasOne(Reasons::className(), ['id' => 'reason_id']);
    }
    public static function getReasonList($id = null){
        if(!empty($id)){
            $query = self::find()
                ->alias('pnrr')
                ->select(['r.name_uz AS reason_name'])
                ->leftJoin(['r' => 'reasons'],'pnrr.reason_id = r.id')
                ->where(['pnrr.plm_notification_list_id' => $id,'pnrr.status_id' => BaseModel::STATUS_ACTIVE])
                ->asArray()
                ->all();
            if(!empty($query)){
                return $query;
            }
        }
        return [];
    }
}
