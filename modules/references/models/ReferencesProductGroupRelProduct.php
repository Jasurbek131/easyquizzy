<?php

namespace app\modules\references\models;

use Yii;

/**
 * This is the model class for table "references_product_group_rel_product".
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_group_id
 *
 * @property Products $products
 * @property ReferencesProductGroup $referencesProductGroup
 */
class ReferencesProductGroupRelProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'references_product_group_rel_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_group_id'], 'default', 'value' => null],
            [['product_id', 'product_group_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['product_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReferencesProductGroup::class, 'targetAttribute' => ['product_group_id' => 'id']],
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
            'product_group_id' => Yii::t('app', 'Product Group ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferencesProductGroup()
    {
        return $this->hasOne(ReferencesProductGroup::class, ['id' => 'product_group_id']);
    }
}
