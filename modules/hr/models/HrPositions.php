<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_positions".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrEmployee[] $hrEmployees
 */
class HrPositions extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz','name_ru'],'required'],
            [['name_uz', 'name_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_ru' => Yii::t('app', 'Name Ru'),
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
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::class, ['hr_position_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->status_id = \app\models\BaseModel::STATUS_ACTIVE;
        }
        return parent::beforeSave($insert);
    }
}
