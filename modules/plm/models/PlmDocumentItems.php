<?php

namespace app\modules\plm\models;

use app\modules\references\models\EquipmentGroup;
use Yii;

/**
 * This is the model class for table "plm_document_items".
 *
 * @property int $id
 * @property int $planned_stop_id
 * @property int $unplanned_stop_id
 * @property float $lifecycle
 * @property float $bypass
 * @property float $target_qty
 * @property int $processing_time_id
 * @property int $document_id
 * @property int $equipment_group_id
 *
 * @property EquipmentGroup $equipmentGroup
 * @property PlmDocuments $plmDocuments
 * @property PlmProcessingTime $plmProcessingTime
 * @property PlmStops $plmStops
 * @property PlmDocItemDefects[] $plmDocItemDefects
 * @property PlmDocItemProducts[] $plmDocItemProducts
 */
class PlmDocumentItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_document_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planned_stop_id', 'unplanned_stop_id', 'processing_time_id', 'document_id', 'equipment_group_id'], 'default', 'value' => null],
            [['planned_stop_id', 'unplanned_stop_id', 'processing_time_id', 'document_id', 'equipment_group_id'], 'integer'],
            [[ 'lifecycle', 'bypass', 'target_qty'], 'number'],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::class, 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocuments::class, 'targetAttribute' => ['document_id' => 'id']],
            [['processing_time_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmProcessingTime::class, 'targetAttribute' => ['processing_time_id' => 'id']],
            [['planned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::class, 'targetAttribute' => ['planned_stop_id' => 'id']],
            [['unplanned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::class, 'targetAttribute' => ['unplanned_stop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'planned_stop_id' => Yii::t('app', 'Planned Stop ID'),
            'unplanned_stop_id' => Yii::t('app', 'Unplanned Stop ID'),
            'processing_time_id' => Yii::t('app', 'Processing Time ID'),
            'document_id' => Yii::t('app', 'Document ID'),
            'equipment_group_id' => Yii::t('app', 'Equipment Group ID'),
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getEquipmentGroup()
    {
        return $this->hasOne(EquipmentGroup::class, ['id' => 'equipment_group_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmDocuments()
    {
        return $this->hasOne(PlmDocuments::class, ['id' => 'document_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmProcessingTime()
    {
        return $this->hasOne(PlmProcessingTime::class, ['id' => 'processing_time_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlanned_stopped()
    {
        return $this->hasOne(PlmStops::class, ['id' => 'planned_stop_id']);
    }
    /**
     * @return yii\db\ActiveQuery
     */
    public function getUnplanned_stopped()
    {
        return $this->hasOne(PlmStops::class, ['id' => 'unplanned_stop_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::class, ['doc_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(PlmDocItemProducts::class, ['document_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasMany(PlmDocItemEquipments::class, ['document_item_id' => 'id']);
    }
}
