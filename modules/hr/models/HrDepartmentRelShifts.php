<?php

namespace app\modules\hr\models;
use app\models;
use app\modules\references\models\Shifts;
use Yii;

/**
 * This is the model class for table "hr_department_rel_shifts".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $shift_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property Shifts $shifts
 */
class HrDepartmentRelShifts extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_department_rel_shifts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'shift_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'shift_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['shift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shifts::className(), 'targetAttribute' => ['shift_id' => 'id']],
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
            'shift_id' => Yii::t('app', 'Shift ID'),
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
    public function getShifts()
    {
        return $this->hasOne(Shifts::className(), ['id' => 'shift_id']);
    }

    public static function getHrRelShift($department_id = null){
        if(!empty($department_id)){
            $data = self::find()
                ->alias('hers')
                ->select([
                    "hers.id AS id",
                    "hrd.name AS dep_name",
                    "sh.name AS shift_name",
                    "sh.start_time AS start_time",
                    "sh.end_time AS end_time",
                    "sl.name_uz as status_name",
                    "sl.id as status"
                ])
                ->leftJoin(['sh' => 'shifts'],'hers.shift_id = sh.id')
                ->leftJoin(['hrd' => 'hr_departments'],'hers.hr_department_id = hrd.id')
                ->leftJoin(['sl' => 'status_list'],'hers.status_id = sl.id')
                ->where(['hers.hr_department_id' => $department_id])
                ->asArray()
                ->orderBy(['hers.id' => SORT_DESC,'hers.status_id' => SORT_ASC])
                ->all();
            return $data ?? [];
        }
        return [];
    }
}
