<?php

namespace app\modules\hr\models;

use app\models\Users;
use Yii;
use yii\helpers\ArrayHelper;

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
     * Agar bo'lim tashkilot bo'lsa root = 1
     */
    const ROOT = 1;

    /**
     * Agar bo'lim tashkilot bo'lmasa root = 0
     */
    const NOT_ROOT = 0;

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
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
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
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * Foydalanuvchiga tegishli tashkilot id larini qaytaradi
     */
    public static function getRootByUser(): array
    {
        $ids = self::find()
            ->where([
                "user_id" => Yii::$app->user->identity->id,
                'is_root' => self::ROOT
            ])
            ->asArray()
            ->all();

        if (!empty($ids))
            $ids = ArrayHelper::getColumn($ids, "hr_department_id");
        return $ids;
    }

    /**
     * @return array
     * Foydalanuvchiga tegishli tashkilot va uning bo'limlarini id larini qaytaradi
     */
    public static function getDepartmentByUser(): array
    {
        $user_root = self::getRootByUser();
        return array_merge($user_root, HrDepartments::getChilds($user_root));
    }
}
