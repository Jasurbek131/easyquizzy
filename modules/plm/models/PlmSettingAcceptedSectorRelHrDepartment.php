<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrDepartments;
use Yii;

/**
 * This is the model class for table "plm_setting_accepted_sector_rel_hr_department".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $plm_sector_list_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property PlmSectorList $plmSectorList
 */
class PlmSettingAcceptedSectorRelHrDepartment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_setting_accepted_sector_rel_hr_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'plm_sector_list_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'plm_sector_list_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['plm_sector_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmSectorList::className(), 'targetAttribute' => ['plm_sector_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_department_id' => Yii::t('app', 'Hr Department ID'),
            'plm_sector_list_id' => Yii::t('app', 'Plm Sector List ID'),
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
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmSectorList()
    {
        return $this->hasOne(PlmSectorList::className(), ['id' => 'plm_sector_list_id']);
    }
}
