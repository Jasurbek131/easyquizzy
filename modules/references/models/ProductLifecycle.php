<?php

namespace app\modules\references\models;

use Yii;

/**
 * This is the model class for table "product_lifecycle".
 *
 * @property int $id
 * @property int $product_id
 * @property int $equipment_group_id
 * @property int $lifecycle
 * @property int $time_type_id
 * @property int $status_id
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property EquipmentGroup $equipmentGroup
 * @property Products $products
 * @property TimeTypesList $timeTypesList
 */
class ProductLifecycle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_lifecycle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'equipment_group_id', 'lifecycle', 'time_type_id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['product_id', 'equipment_group_id', 'lifecycle', 'time_type_id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::className(), 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['time_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimeTypesList::className(), 'targetAttribute' => ['time_type_id' => 'id']],
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
            'equipment_group_id' => Yii::t('app', 'Equipment Group ID'),
            'lifecycle' => Yii::t('app', 'Lifecycle'),
            'time_type_id' => Yii::t('app', 'Time Type ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentGroup()
    {
        return $this->hasOne(EquipmentGroup::className(), ['id' => 'equipment_group_id']);
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
    public function getTimeTypesList()
    {
        return $this->hasOne(TimeTypesList::className(), ['id' => 'time_type_id']);
    }
}
