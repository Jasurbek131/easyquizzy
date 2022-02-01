<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "users_relation_hr_departments".
 *
 * @property int $id
 * @property int $user_id
 * @property int $hr_department_id
 * @property bool $is_root
 *
 * @property HrDepartments $hrDepartments
 * @property Users $users
 */
class UsersRelationHrDepartments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_relation_hr_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'hr_department_id'], 'default', 'value' => null],
            [['user_id', 'hr_department_id'], 'integer'],
            [['is_root'], 'boolean'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'hr_department_id' => 'Hr Department ID',
            'is_root' => 'Is Root',
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
    public function getUsers()
    {
        return $this->hasOne(Users::className(), [id => user_id]);
    }
}
