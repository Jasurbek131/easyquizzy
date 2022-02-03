<?php

namespace app\modules\hr\models;

use BaseModel;
use Yii;

/**
 * This is the model class for table "hr_departments".
 *
 * @property int $id
 * @property int $hr_organisation_id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $token
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrOrganisations $hrOrganisations
 * @property HrEmployee[] $hrEmployees
 */
class HrDepartments extends BaseModel
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
            [['hr_organisation_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_organisation_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz', 'name_ru', 'token'], 'string', 'max' => 255],
            [['hr_organisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrOrganisations::className(), 'targetAttribute' => ['hr_organisation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_organisation_id' => Yii::t('app', 'Hr Organisation ID'),
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
    public function getHrOrganisations()
    {
        return $this->hasOne(HrOrganisations::className(), ['id' => 'hr_organisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['hr_department_id' => 'id']);
    }
}
