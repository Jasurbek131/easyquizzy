<?php

namespace app\modules\plm\models;

use app\modules\references\models\Products;
use Yii;

/**
 * This is the model class for table "plm_document_items".
 *
 * @property int $id
 * @property int $product_id
 * @property int $planned_stop_id
 * @property int $unplanned_stop_id
 * @property int $repaired_id
 * @property int $scrapped_id
 * @property int $processing_time_id
 * @property int $qty
 * @property int $fact_qty
 *
 * @property PlmProcessingTime $plmProcessingTime
 * @property Products $products
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
            [['product_id', 'planned_stop_id', 'unplanned_stop_id', 'repaired_id', 'scrapped_id', 'processing_time_id', 'qty', 'fact_qty'], 'default', 'value' => null],
            [['product_id', 'planned_stop_id', 'unplanned_stop_id', 'repaired_id', 'scrapped_id', 'processing_time_id', 'qty', 'fact_qty'], 'integer'],
            [['repaired_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['repaired_id' => 'id']],
            [['scrapped_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['scrapped_id' => 'id']],
            [['processing_time_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmProcessingTime::className(), 'targetAttribute' => ['processing_time_id' => 'id']],
            [['planned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::className(), 'targetAttribute' => ['planned_stop_id' => 'id']],
            [['unplanned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::className(), 'targetAttribute' => ['unplanned_stop_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'planned_stop_id' => Yii::t('app', 'Planned Stop ID'),
            'unplanned_stop_id' => Yii::t('app', 'Unplanned Stop ID'),
            'repaired_id' => Yii::t('app', 'Repaired ID'),
            'scrapped_id' => Yii::t('app', 'Scrapped ID'),
            'processing_time_id' => Yii::t('app', 'Processing Time ID'),
            'qty' => Yii::t('app', 'Qty'),
            'fact_qty' => Yii::t('app', 'Fact Qty'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepaired()
    {
        return $this->hasOne(Defects::className(), ['id' => 'repaired_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScrapped()
    {
        return $this->hasOne(Defects::className(), ['id' => 'scrapped_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmProcessingTime()
    {
        return $this->hasOne(PlmProcessingTime::className(), ['id' => 'processing_time_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlannedStopped()
    {
        return $this->hasOne(PlmStops::className(), ['id' => 'planned_stop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnplannedStopped()
    {
        return $this->hasOne(PlmStops::className(), ['id' => 'unplanned_stop_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
}
