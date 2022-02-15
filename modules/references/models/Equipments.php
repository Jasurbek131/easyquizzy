<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "equipments".
 *
 * @property int $id
 * @property string $name
 * @property int $equipment_type_id
 * @property int $status_id
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property EquipmentTypes $equipmentTypes
 * @property EquipmentGroupRelationEquipment[] $equipmentGroupRelationEquipments
 */
class Equipments extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'equipment_type_id', 'status_id'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['equipment_type_id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['equipment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentTypes::class, 'targetAttribute' => ['equipment_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'equipment_type_id' => Yii::t('app', 'Equipment Type'),
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
    public function getEquipmentTypes()
    {
        return $this->hasOne(EquipmentTypes::class, ['id' => 'equipment_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentGroupRelationEquipments()
    {
        return $this->hasMany(EquipmentGroupRelationEquipment::class, ['equipment_id' => 'id']);
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($key = null, $isArray = false) {
        $list = self::find()->select(['id as value', 'name as label'])->asArray()->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @param bool $isMap
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListForSelect($isMap = false)
    {
        $list = self::find()->select(["id as value", "name as label"])->asArray()->all();

        if ($isMap && !empty($list))
            return ArrayHelper::map($list, "id", "name");

        return $list;
    }

}
