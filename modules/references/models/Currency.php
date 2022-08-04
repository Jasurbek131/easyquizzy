<?php

namespace app\modules\references\models;

use app\modules\references\models\EquipmentGroup;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $name
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property EquipmentGroup[] $equipmentGroups
 */
class Currency extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
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
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentGroups()
    {
        return $this->hasMany(EquipmentGroup::class, ['unplanned_currency_id' => 'id']);
    }

    /**
     * @param false $isMap
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($isMap = false)
    {

        $list = self::find()
            ->select([
                "value" => "id",
                "label" => "name",
            ])
            ->where([
                'status_id' => self::STATUS_ACTIVE
            ])
            ->asArray()
            ->all();

        if ($isMap && !empty($list))
            return ArrayHelper::map($list, "value", "label");

        return $list;
    }
}
