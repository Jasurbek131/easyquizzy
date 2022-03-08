<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\UsersRelationHrDepartments;
use app\modules\references\models\Shifts;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "plm_documents".
 *
 * @property int $id
 * @property string $doc_number
 * @property string $reg_date
 * @property int $hr_department_id
 * @property int $organisation_id
 * @property string $add_info
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property int $shift_id
 *
 * @property HrDepartments $hrDepartments
 * @property Shifts $shifts
 * @property PlmDocumentItems[] $plmDocumentItems
 */
class PlmDocuments extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_date'], 'safe'],
            [['hr_department_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'shift_id'], 'default', 'value' => null],
            [['hr_department_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'shift_id', 'organisation_id'], 'integer'],
            [['add_info'], 'string'],
            [['doc_number'], 'string', 'max' => 255],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['organisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['organisation_id' => 'id']],
            [['shift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shifts::class, 'targetAttribute' => ['shift_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'hr_department_id' => Yii::t('app', 'Hr Department ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'shift_id' => Yii::t('app', 'Shift ID'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $lastDoc = self::find()->orderBy(['id' => SORT_DESC])->one();
            $last = 1;
            if (!empty($lastDoc))
                $last = $lastDoc["id"] + 1;
            $this->doc_number = "PD-" . $last;
            $organisation = UsersRelationHrDepartments::findOne(["user_id" => Yii::$app->user->identity->id, 'is_root' => UsersRelationHrDepartments::ROOT]);
            $this->organisation_id = $organisation ? $organisation->hr_department_id : "";
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'organisation_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getShifts()
    {
        return $this->hasOne(Shifts::class, ['id' => 'shift_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasMany(PlmDocumentItems::class, ['document_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlm_document_items()
    {
        return $this->hasMany(PlmDocumentItems::class, ['document_id' => 'id']);
    }
}
