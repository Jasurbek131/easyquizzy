<?php

namespace app\modules\hr\models;

use app\modules\references\models\Equipments;
use Yii;

/**
 * This is the model class for table "hr_department_rel_equipment".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $equipment_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Equipments $equipments
 * @property HrDepartments $hrDepartments
 */
class HrDepartmentRelEquipment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_department_rel_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::className(), 'targetAttribute' => ['equipment_id' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
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
            'equipment_id' => Yii::t('app', 'Equipment ID'),
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
    public function getEquipments()
    {
        return $this->hasOne(Equipments::className(), ['id' => 'equipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_department_id']);
    }

    public static function getHrRelEquipment($department_id = null){
        if(!empty($department_id)){
            $data = self::find()
                ->alias('here')
                ->select([
                    "here.id AS id",
                    "hrd.name AS dep_name",
                    "e.name AS equipment_name",
                    "et.name AS equipment_type_time",
                    "sl.name_uz as status_name",
                    "sl.id as status"
                ])
                ->leftJoin(['e' => 'equipments'],'here.equipment_id = e.id')
                ->leftJoin(['et' => 'equipment_types'],'e.equipment_type_id = et.id')
                ->leftJoin(['hrd' => 'hr_departments'],'here.hr_department_id = hrd.id')
                ->leftJoin(['sl' => 'status_list'],'here.status_id = sl.id')
                ->where(['here.hr_department_id' => $department_id])
                ->andWhere(['here.status_id' => \app\models\BaseModel::STATUS_ACTIVE])
                ->asArray()
                ->all();
            return $data ?? [];
        }
        return [];
    }
}
