<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_employee".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $fathername
 * @property string $phone_number
 * @property string $email
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property HrEmployeeRelPosition[] $hrEmployeeRelPosition
 * @property HrPositions $hrPositions
 */
class HrEmployee extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['firstname', 'lastname', 'fathername', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'fathername' => Yii::t('app', 'Fathername'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getHrEmployeeRelPosition()
    {
        return $this->hasMany(HrEmployeeRelPosition::class, ['hr_employee_id' => 'id']);
    }

    /**
     * @param bool $isMap
     * @return array
     */
    public static function getList($isMap = true):array
    {
        $list = self::find()
            ->where([
                'status_id' => \app\models\BaseModel::STATUS_ACTIVE
            ])
            ->asArray()
            ->all();
        if ($isMap && !empty($list))
            return ArrayHelper::map($list,'id', function ($m){
                return $m['lastname']." ".$m['lastname']." ".$m['fathername'];
            });

        return $list;
    }

}
