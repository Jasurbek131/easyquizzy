<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_employee".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $fathername
 * @property string $phone_number
 * @property string $email
 * @property int $hr_department_id
 * @property int $hr_position_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property HrPositions $hrPositions
 */
class HrEmployee extends \yii\db\ActiveRecord
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
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['firstname', 'lastname', 'fathername', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 30],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['hr_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrPositions::className(), 'targetAttribute' => ['hr_position_id' => 'id']],
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
            'hr_department_id' => Yii::t('app', 'Hr Department ID'),
            'hr_position_id' => Yii::t('app', 'Hr Position ID'),
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
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::className(), [id => hr_department_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPositions()
    {
        return $this->hasOne(HrPositions::className(), [id => hr_position_id]);
    }
}
