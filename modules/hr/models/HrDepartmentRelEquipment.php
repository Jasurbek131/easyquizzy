<?php

namespace app\modules\hr\models;

use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\Equipments;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 * @property-read ActiveQuery $equipmentGroup
 * @property string $equipment_group_id [integer]
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
            [['hr_department_id', 'equipment_id', 'equipment_group_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['equipment_group_id','hr_department_id'],'required'],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::class, 'targetAttribute' => ['equipment_id' => 'id']],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::class, 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
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
            'equipment_group_id' => Yii::t('app', 'Equipment Group ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasOne(Equipments::class, ['id' => 'equipment_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEquipmentGroup()
    {
        return $this->hasOne(EquipmentGroup::class, ['id' => 'equipment_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @param $department_id
     * @return HrDepartmentRelEquipment[]|array|ActiveRecord[]
     */
    public static function getHrRelEquipment($department_id = null)
    {
        if (!empty($department_id)) {
            $data = self::find()
                ->alias('here')
                ->select([
                    "here.id AS id",
                    "hrd.name AS dep_name",
                    "eg.name AS equipment_name",
//                    "et.name AS equipment_type_time",
                    "sl.name_uz as status_name",
                    "sl.id as status"
                ])
                ->leftJoin(['eg' => 'equipment_group'], 'here.equipment_group_id = eg.id')
//                ->leftJoin(['et' => 'equipment_types'], 'e.equipment_type_id = et.id')
                ->leftJoin(['hrd' => 'hr_departments'], 'here.hr_department_id = hrd.id')
                ->leftJoin(['sl' => 'status_list'], 'here.status_id = sl.id')
                ->where([
                    'here.hr_department_id' => $department_id,
                    'here.status_id' => 1
                ])
                ->asArray()
                ->orderBy(['here.id' => SORT_DESC, 'here.status_id' => SORT_ASC])
                ->all();
            return $data ?? [];
        }
        return [];
    }
}
