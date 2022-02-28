<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_employee".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $fathername
 * @property string $phone_number
 * @property string $email
 * @property int $status_id
 * @property int $hr_department_id
 * @property int $hr_organisation_id
 * @property int $hr_position_id
 * @property string $begin_date
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property HrEmployeeRelPosition $hrEmployeeActivePosition
 * @property HrEmployeeRelPosition[] $hrEmployeeRelPosition
 * @property HrPositions $hrPositions
 */
class HrEmployee extends BaseModel
{
    /**
     * @var
     * Bo'lim ma'lumotlarini olish uchun
     */
    public $hr_department_id;

    /**
     * @var
     * Tashkilot ma'lumotlarini olish uchun
     */
    public $hr_organisation_id;

    /**
     * @var
     * Lavozim ma'lumotlarini olish uchun
     */
    public $hr_position_id;

    /**
     * @var
     * Hodim ishga kirgan sanasi olish uchun
     */
    public $begin_date;

    /**
     * @var bool
     * Hdoim malumotlari yangilanayotgan bo'lsa: true bo'ladi
     */
    public $isUpdate = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['firstname', 'lastname', 'fathername', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 30],
            [['hr_department_id', 'hr_organisation_id', 'hr_position_id', 'begin_date','firstname', 'lastname', 'fathername'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'fathername' => Yii::t('app', 'Fathername'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'hr_department_id' => Yii::t('app', 'Hr Department'),
            'hr_organisation_id' => Yii::t('app', 'Hr Organisation'),
            'hr_position_id' => Yii::t('app', 'Hr Position'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'email' => Yii::t('app', 'Email'),
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
    public function getHrEmployeeRelPosition()
    {
        return $this->hasMany(HrEmployeeRelPosition::class, ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeActivePosition()
    {
        return $this->hasOne(HrEmployeeRelPosition::class, ['hr_employee_id' => 'id'])->where(['hr_employee_rel_position.status_id' => \app\models\BaseModel::STATUS_ACTIVE]);
    }

    /**
     * @param bool $isMap
     * @return array
     */
    public static function getList($isMap = true):array
    {
        $list = self::find()
            ->where([
                'status_id' => \app\models\BaseModel::STATUS_ACTIVE
            ])
            ->asArray()
            ->all();
        if ($isMap && !empty($list))
            return ArrayHelper::map($list,'id', function ($m){
                return $m['firstname']." ".$m['lastname']." ".$m['fathername'];
            });

        return $list;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getEmployeeData($id): array
    {
        return  HrEmployeeRelPosition::find()
            ->alias('hrerp')
            ->select([
                'hrd.name AS department_name',
                'hrp.name_uz AS position_name',
                'hrerp.begin_date AS begin_date',
                'hrerp.end_date AS end_date',
                'sl.name_uz status_name',
                'sl.id status'
            ])
            ->leftJoin(['hrd'=>'hr_departments'],'hrerp.hr_department_id = hrd.id')
            ->leftJoin(['hrp'=>'hr_positions'],'hrerp.hr_position_id = hrp.id')
            ->leftJoin(['sl' => 'status_list'],'hrerp.status_id = sl.id')
            ->where(['hr_employee_id' => $id])
            ->orderBy(['hrerp.id' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * @return array
     */
    public function saveEmployee():array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{

            if (!$this->save())
                $response = [
                    'status' => false,
                    'message' => 'Hr employee not saved',
                    'errors' => $this->getErrors()
                ];

            $newCreatePosition = true;
            if ($response['status']){

                if ($this->isUpdate && !empty($this->hrEmployeeActivePosition)){
                    if ($this->hrEmployeeActivePosition->hr_department_id != $this->hr_department_id || $this->hrEmployeeActivePosition->hr_position_id != $this->hr_position_id)
                    {
                        $this->hrEmployeeActivePosition->end_date = date('Y-m-d H:i:s');
                        $this->hrEmployeeActivePosition->status_id = \app\models\BaseModel::STATUS_INACTIVE;
                        if (!$this->hrEmployeeActivePosition->save())
                            $response = [
                                'status' => false,
                                'message' => 'Old postion not saved',
                                'errors' => $this->hrEmployeeActivePosition->getErrors()
                            ];
                    }else{
                        $newCreatePosition = false;
                        $position = $this->hrEmployeeActivePosition;
                        $position->begin_date = $this->begin_date;
                    }
                }

                if ($newCreatePosition && $response['status'])
                {
                    $position =  new HrEmployeeRelPosition([
                        'hr_department_id' => $this->hr_department_id,
                        'hr_position_id' => $this->hr_position_id,
                        'hr_organisation_id' => $this->hr_organisation_id,
                        'hr_employee_id' => $this->id,
                        'begin_date' => $this->begin_date ? date('Y-m-d H:i:s', strtotime($this->begin_date)) : "",
                    ]);
                }

               if ($response['status'])
                   if (!$position->save())
                       $response = [
                           'status' => false,
                           'message' => 'Hr position not saved',
                           'errors' => $position->getErrors()
                       ];
            }

            if($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();

        } catch(\Exception $e){
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }
}
