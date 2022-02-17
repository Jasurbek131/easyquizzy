<?php

namespace app\modules\plm\models;

use app\modules\references\models\Defects;
use app\modules\references\models\Reasons;
use Yii;

/**
 * This is the model class for table "plm_notifications_list".
 *
 * @property int $id
 * @property int $plm_doc_item_id
 * @property string $begin_time
 * @property string $end_time
 * @property int $defect_id
 * @property int $defect_type_id
 * @property int $defect_count
 * @property int $reason_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property string $add_info
 *
 * @property Defects $defects
 * @property PlmDocumentItems $plmDocumentItems
 * @property Reasons $reasons
 */
class PlmNotificationsList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_notifications_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plm_doc_item_id', 'defect_id', 'defect_type_id', 'defect_count', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['plm_doc_item_id', 'defect_id', 'defect_type_id', 'defect_count', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['begin_time', 'end_time'], 'safe'],
            [['add_info'], 'string'],
            [['defect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['defect_id' => 'id']],
            [['plm_doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::className(), 'targetAttribute' => ['plm_doc_item_id' => 'id']],
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
            'plm_doc_item_id' => Yii::t('app', 'Plm Doc Item ID'),
            'begin_time' => Yii::t('app', 'Begin Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'defect_id' => Yii::t('app', 'Defect ID'),
            'defect_type_id' => Yii::t('app', 'Defect Type ID'),
            'defect_count' => Yii::t('app', 'Defect Count'),
            'reason_id' => Yii::t('app', 'Reason ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'add_info' => Yii::t('app', 'Add Info'),
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
    public function getPlmDocumentItems()
    {
        return $this->hasOne(PlmDocumentItems::className(), ['id' => 'plm_doc_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReasons()
    {
        return $this->hasOne(Reasons::className(), ['id' => 'reason_id']);
    }
}
