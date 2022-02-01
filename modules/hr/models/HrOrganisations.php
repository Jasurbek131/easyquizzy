<?php

namespace app\modules\hr\models;

use app\models\Users;
use kartik\tree\models\Tree;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr-organisations".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $slug
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property string $name_uz [varchar(255)]
 * @property bool $child_allowed [boolean]
 */
class HrOrganisations extends Tree
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_organisations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name', 'name_ru', 'slug'], 'string', 'max' => 255],
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
            'name_ru' => Yii::t('app', 'Name Ru'),
            'slug' => Yii::t('app', 'Slug'),
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
        return $this->hasMany(HrDepartments::class, ['hr_organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['hr_organisation_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->status_id = \app\models\BaseModel::STATUS_ACTIVE;

        return parent::beforeSave($insert);
    }


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
