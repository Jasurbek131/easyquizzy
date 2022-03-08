<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "equipment_types".
 *
 * @property int $id
 * @property string $name
 * @property int $status_id
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Equipments[] $equipments
 */
class EquipmentTypes extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status_id'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
    public function getEquipments()
    {
        return $this->hasMany(Equipments::className(), ['equipment_type_id' => 'id']);
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
}
