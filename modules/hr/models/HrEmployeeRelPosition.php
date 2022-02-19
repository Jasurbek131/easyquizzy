<?php

namespace app\modules\hr\models;

use app\models\Users;
use app\modules\plm\models\BaseModel;
use Yii;

/**
 * This is the model class for table "hr_employee_rel_position".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $hr_organisation_id
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
 * @property HrDepartments $hrOrganisations
 * @property HrEmployee $hrEmployee
 * @property HrPositions $hrPositions
 */
class HrEmployeeRelPosition extends BaseModel
{
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
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'hr_employee_id'], 'default', 'value' => null],
            [['hr_department_id', 'hr_position_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'hr_employee_id'], 'integer'],
            [['end_date'], 'safe'],
            [['begin_date'],'required'],
            [['status_id'],'default','value' => \app\models\BaseModel::STATUS_ACTIVE],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['hr_organisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_organisation_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['hr_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrPositions::class, 'targetAttribute' => ['hr_position_id' => 'id']],
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
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrOrganisations()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_organisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'hr_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPositions()
    {
        return $this->hasOne(HrPositions::class, ['id' => 'hr_position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'hr_employee_id']);
    }
    public static function getActiveHrDepartment(){
        $user_id = Yii::$app->user->identity->id;
            return Users::find()
                ->alias('u')
                ->select(['hrerp.*'])
                ->leftJoin(['hreru' => 'hr_employee_rel_users'],'hreru.user_id = u.id')
                ->leftJoin(['hrerp' => 'hr_employee_rel_position'],'hrerp.hr_employee_id = hreru.hr_employee_id')
                ->where(['u.id' => $user_id,'hreru.status_id' => BaseModel::STATUS_ACTIVE,'hrerp.status_id' => BaseModel::STATUS_ACTIVE])
                ->orderBy(['hrerp.id' => SORT_DESC])
                ->one();
    }


}
