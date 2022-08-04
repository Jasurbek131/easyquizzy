<?php

namespace app\modules\plm\models;

use app\modules\references\models\ProductLifecycle;
use app\modules\references\models\Products;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "plm_doc_item_products".
 *
 * @property int $id
 * @property int $document_item_id
 * @property int $product_id
 * @property int $product_lifecycle_id
 * @property int $qty
 * @property int $fact_qty
 *
 * @property PlmDocumentItems $plmDocumentItems
 * @property Products $products
 * @property PlmDocItemDefects[] $plmDocItemDefects
 */
class PlmDocItemProducts extends ActiveRecord
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
            [['document_item_id', 'product_id', 'product_lifecycle_id', 'qty', 'fact_qty'], 'default', 'value' => null],
            [['document_item_id', 'product_id', 'product_lifecycle_id', 'qty', 'fact_qty'], 'integer'],
            [['document_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::class, 'targetAttribute' => ['document_item_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['product_lifecycle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductLifecycle::class, 'targetAttribute' => ['product_lifecycle_id' => 'id']],
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
            'product_lifecycle_id' => Yii::t('app', 'Product Lifecycle'),
            'qty' => Yii::t('app', 'Qty'),
            'fact_qty' => Yii::t('app', 'Fact Qty'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasOne(PlmDocumentItems::class, ['id' => 'document_item_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductLifecycle()
    {
        return $this->hasOne(ProductLifecycle::class, ['id' => 'product_lifecycle_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::class, ['doc_item_product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRepaired()
    {
        return $this->hasMany(PlmDocItemDefects::class, ['doc_item_product_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getScrapped()
    {
        return $this->hasMany(PlmDocItemDefects::class, ['doc_item_product_id' => 'id']);
    }
}
