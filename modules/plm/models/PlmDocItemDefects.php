<?php

namespace app\modules\plm\models;

use app\modules\references\models\Defects;
use Yii;

/**
 * This is the model class for table "plm_doc_item_defects".
 *
 * @property int $id
 * @property int $type
 * @property int $doc_item_id
 * @property int $defect_id
 * @property int $qty
 * @property int $status_id
 *
 * @property Defects $defects
 * @property PlmDocumentItems $plmDocumentItems
 */
class PlmDocItemDefects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_doc_item_defects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'doc_item_id', 'defect_id', 'qty', 'status_id'], 'default', 'value' => null],
            [['type', 'doc_item_id', 'defect_id', 'qty', 'status_id'], 'integer'],
            [['defect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['defect_id' => 'id']],
            [['doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::className(), 'targetAttribute' => ['doc_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'doc_item_id' => Yii::t('app', 'Doc Item ID'),
            'defect_id' => Yii::t('app', 'Defect ID'),
            'qty' => Yii::t('app', 'Qty'),
            'status_id' => Yii::t('app', 'Status ID'),
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
        return $this->hasOne(PlmDocumentItems::className(), ['id' => 'doc_item_id']);
    }
}
