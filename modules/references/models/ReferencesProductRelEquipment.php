<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "references_product_rel_equipment".
 *
 * @property int $id
 * @property int $product_id
 * @property int $equipment_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Equipments $equipments
 * @property Products $products
 */
class ReferencesProductRelEquipment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'references_product_rel_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['product_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::class, 'targetAttribute' => ['equipment_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'equipment_id' => Yii::t('app', 'Equipment ID'),
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
    public function getEquipments()
    {
        return $this->hasOne(Equipments::class, ['id' => 'equipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->status_id = \app\models\BaseModel::STATUS_ACTIVE;

        return parent::beforeSave($insert);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getEquipmentsByProduct($id): array
    {
        $equipments = self::find()
            ->where([
                "product_id" => $id
            ])
            ->asArray()
            ->all();
        if (!empty($equipments))
            return  ArrayHelper::getColumn($equipments, "id");
        return [];
    }
}
