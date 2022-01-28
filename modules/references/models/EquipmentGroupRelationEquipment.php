<?php

namespace app\modules\references\models;

use Yii;

/**
 * This is the model class for table "equipment_group_relation_equipment".
 *
 * @property int $id
 * @property int $equipment_group_id
 * @property int $equipment_id
 * @property int $work_order
 * @property int $status_id
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property EquipmentGroup $equipmentGroup
 * @property Equipments $equipments
 */
class EquipmentGroupRelationEquipment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_group_relation_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['equipment_group_id', 'equipment_id', 'work_order', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['equipment_group_id', 'equipment_id', 'work_order', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::className(), 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::className(), 'targetAttribute' => ['equipment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'equipment_group_id' => Yii::t('app', 'Equipment Group ID'),
            'equipment_id' => Yii::t('app', 'Equipment ID'),
            'work_order' => Yii::t('app', 'Work Order'),
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
        return $this->hasOne(EquipmentGroup::className(), [id => equipment_group_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasOne(Equipments::className(), [id => equipment_id]);
    }
}
