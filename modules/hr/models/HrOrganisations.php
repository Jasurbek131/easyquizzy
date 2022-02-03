<?php

namespace app\modules\hr\models;

use app\models\Users;
use kartik\tree\models\Tree;
use Yii;

/**
 * This is the model class for table "hr-organisations".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $slug
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments[] $hrDepartmentss
 * @property Users[] $userss
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
            [['name_uz', 'name_ru', 'slug'], 'string', 'max' => 255],
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
    public function getHrDepartmentss()
    {
        return $this->hasMany(HrDepartments::className(), ['hr_organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserss()
    {
        return $this->hasMany(Users::className(), ['hr_organisation_id' => 'id']);
    }

    public static function getOrganisationsList($isArray = false)
    {
        $list = self::find()
            ->addOrderBy('root, lft');
//            ->filterWhere(['id' => self::getIdListByUser()]);
        if ($isArray) {
            return $list->select(['id as value', 'name as label'])->asArray()->all();
        }
        return $list;
    }
}
