<?php

namespace app\modules\plm\models;

use Yii;

/**
 * This is the model class for table "plm_unplanned_stop".
 *
 * @property int $id
 * @property int $doc_id
 * @property string $begin_date
 * @property string $end_time
 * @property string $add_info
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property PlmDocuments $plmDocuments
 */
class PlmUnplannedStop extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_unplanned_stop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['doc_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['begin_date', 'end_time'], 'safe'],
            [['add_info'], 'string'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocuments::className(), 'targetAttribute' => ['doc_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_time' => Yii::t('app', 'End Time'),
            'add_info' => Yii::t('app', 'Add Info'),
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
    public function getPlmDocuments()
    {
        return $this->hasOne(PlmDocuments::className(), ['id' => 'doc_id']);
    }
}
