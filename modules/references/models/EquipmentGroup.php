<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "equipment_group".
 *
 * @property int $id
 * @property string $name
 * @property int $status_id
 * @property int $created_at
 * @property float $value
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property EquipmentGroupRelationEquipment[] $equipmentGroupRelationEquipments
 * @property ProductLifecycle[] $productLifecycles
 */
class EquipmentGroup extends BaseModel
{
    public $equipments;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status_id', 'value'], 'required'],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['equipments'], 'safe']
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
    public function getEquipmentGroupRelationEquipments()
    {
        return $this->hasMany(EquipmentGroupRelationEquipment::className(), ['equipment_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLifecycles()
    {
        return $this->hasMany(ProductLifecycle::className(), ['equipment_group_id' => 'id']);
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function getList($key = null, $isArray = false) {
        if (!is_null($key)){
            $one = self::findOne($key);
            if (!empty($one)) {
                return $one['name'];
            }
            return "";
        }
        $list = self::find()
            ->select(['id as value', "name as label"])
            ->asArray()
            ->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])
            ->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }
}
