<?php

namespace app\modules\hr\models;

use app\modules\references\models\Defects;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "hr_department_rel_defects".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $defect_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Defects $defects
 * @property HrDepartments $hrDepartments
 */
class HrDepartmentRelDefects extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_department_rel_defects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'defect_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'defect_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['defect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['defect_id' => 'id']],
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
            'defect_id' => Yii::t('app', 'Defect ID'),
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
    public function getDefects()
    {
        return $this->hasOne(Defects::className(), ['id' => 'defect_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_department_id']);
    }

    public static function getHrRelDefect($department_id = null)
    {
        if (!empty($department_id)) {
            $data = self::find()
                ->alias('herd')
                ->select([
                    "herd.id AS id",
                    "hrd.name AS dep_name",
                    "d.name_uz AS defect_name",
                    "d.type AS defect_type",
                    "sl.name_uz as status_name",
                    "sl.id as status"
                ])
                ->leftJoin(['d' => 'defects'], 'herd.defect_id = d.id')
                ->leftJoin(['hrd' => 'hr_departments'], 'herd.hr_department_id = hrd.id')
                ->leftJoin(['sl' => 'status_list'], 'herd.status_id = sl.id')
                ->where(['herd.hr_department_id' => $department_id])
                ->orderBy(['herd.id' => SORT_DESC, 'herd.status_id' => SORT_ASC])
                ->asArray()
                ->all();
            return $data ?? [];
        }
        return [];
    }
}
