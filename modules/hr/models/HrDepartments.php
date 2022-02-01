<?php

namespace app\modules\hr\models;

use kartik\tree\models\Tree;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_departments".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ru
 * @property string $token
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrEmployee[] $hrEmployees
 */
class HrDepartments extends Tree
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name','name_uz', 'name_ru', 'token'], 'string', 'max' => 255],
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
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'token' => Yii::t('app', 'Token'),
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
        return $this->hasMany(HrEmployee::className(), ['hr_department_id' => 'id']);
    }

    public static function getList($key = null, $isArray = false) {
        $list = self::find()->select(['id as value', 'name_uz as label'])->asArray()->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }
}
