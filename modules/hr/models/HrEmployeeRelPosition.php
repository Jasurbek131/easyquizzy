<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_employee_rel_position".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $hr_position_id
 * @property int $hr_employee_id
 * @property string $begin_date
 * @property string $end_date
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property HrEmployee $hrEmployee
 * @property HrPositions $hrPositions
 */
class HrEmployeeRelPosition extends BaseModel
{
    const SCENARIO_CREATE = 'scenario-create';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employee_rel_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id','hr_position_id'],'required','on' => [self::SCENARIO_CREATE]],
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'hr_employee_id'], 'default', 'value' => null],
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'hr_employee_id'], 'integer'],
            [['end_date'], 'safe'],
            [['begin_date'],'required'],
            [['status_id'],'default','value' => \app\models\BaseModel::STATUS_ACTIVE],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
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
            'hr_department_id' => Yii::t('app', 'Hr Department ID'),
            'hr_position_id' => Yii::t('app', 'Hr Positon ID'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'hr_employee_id' => Yii::t('app', 'Hr Employee ID'),
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
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPositions()
    {
        return $this->hasOne(HrPositions::className(), ['id' => 'hr_position_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }
}
