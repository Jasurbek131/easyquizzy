<?php

namespace app\modules\plm\models;

use app\modules\references\models\Products;
use Yii;

/**
 * This is the model class for table "plm_doc_item_products".
 *
 * @property int $id
 * @property int $document_item_id
 * @property int $product_id
 * @property int $qty
 * @property int $fact_qty
 *
 * @property PlmDocumentItems $plmDocumentItems
 * @property Products $products
 * @property PlmDocItemDefects[] $plmDocItemDefects
 */
class PlmDocItemProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_doc_item_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_item_id', 'product_id', 'qty', 'fact_qty'], 'default', 'value' => null],
            [['document_item_id', 'product_id', 'qty', 'fact_qty'], 'integer'],
            [['document_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::className(), 'targetAttribute' => ['document_item_id' => 'id']],
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
            'document_item_id' => Yii::t('app', 'Document Item ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'qty' => Yii::t('app', 'Qty'),
            'fact_qty' => Yii::t('app', 'Fact Qty'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasOne(PlmDocumentItems::className(), ['id' => 'document_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::className(), ['doc_item_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepaired()
    {
        return $this->hasMany(PlmDocItemDefects::className(), ['doc_item_product_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScrapped()
    {
        return $this->hasMany(PlmDocItemDefects::className(), ['doc_item_product_id' => 'id']);
    }
}
