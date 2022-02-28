<?php

namespace app\modules\plm\models;

use app\modules\references\models\Equipments;
use Yii;

/**
 * This is the model class for table "plm_doc_item_equipments".
 *
 * @property int $id
 * @property int $document_item_id
 * @property int $equipment_id
 *
 * @property Equipments $equipments
 * @property PlmDocumentItems $plmDocumentItems
 */
class PlmDocItemEquipments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_doc_item_equipments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_item_id', 'equipment_id'], 'default', 'value' => null],
            [['document_item_id', 'equipment_id'], 'integer'],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::class, 'targetAttribute' => ['equipment_id' => 'id']],
            [['document_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::class, 'targetAttribute' => ['document_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_item_id' => Yii::t('app', 'Document Item ID'),
            'equipment_id' => Yii::t('app', 'Equipment ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasOne(Equipments::class, ['id' => 'equipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasOne(PlmDocumentItems::class, ['id' => 'document_item_id']);
    }
}
