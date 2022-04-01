<?php

namespace app\modules\references\models;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartmentRelDefects;
use app\modules\hr\models\HrDepartments;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocumentItems;
use app\widgets\Language;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "defects".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property int $type
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartmentRelDefects[] $hrDepartmentRelDefects
 * @property PlmDocItemDefects[] $plmDocItemDefects
 * @property-read ActiveQuery $plmDocumentItems
 * @property-read mixed $hrDepartment
 * @property string $hr_department_id [integer]
 */
class Defects extends BaseModel
{
    const REPAIRED_TYPE = 1; // ta'mirlangan ishlar uchun tur
    const INVALID_TYPE = 2;   // yaroqsiz ishlar uchun tur

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'defects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['type', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz', 'name_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'type' => Yii::t('app', 'Defect Type'),
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
    public function getHrDepartmentRelDefects()
    {
        return $this->hasMany(HrDepartmentRelDefects::className(), ['defect_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::className(), ['defect_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasMany(PlmDocumentItems::className(), ['defect_id' => 'id']);
    }

    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDefectTypeList($key = null)
    {
        $result = [
            self::REPAIRED_TYPE => Yii::t('app', 'Repaired Type'),
            self::INVALID_TYPE => Yii::t('app', 'Invalid Type'),
        ];
        if (!empty($key)) {
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|ActiveRecord[]
     * @throws \Exception
     */
    public static function getList($key = null, $isArray = false)
    {
        $language = Language::widget();
        $list = self::find()->asArray()->select(['id as id', "{$language} as name"])->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'id', 'name');
    }

    /**
     * @param $type
     * @param $department_id
     * @return Defects[]|array|ActiveRecord[]
     */
    public static function getListByType($type, $department_id)
    {
        $language = Yii::$app->language;

        return Defects::find()
            ->alias('defects')
            ->select([
                'defects.id as value',
                "defects.name_{$language} as label",
                "SUM(0) as count"
            ])
            ->leftJoin(['defect_rel' => HrDepartmentRelDefects::tableName()], 'defect_rel.defect_id = defects.id')
            ->where([
                'defects.type' => $type,
                'defects.status_id' => BaseModel::STATUS_ACTIVE,
                'defect_rel.status_id' => BaseModel::STATUS_ACTIVE,
            ])
            ->andFilterWhere(['IN', 'defect_rel.hr_department_id', $department_id])
            ->groupBy('defects.id')
            ->asArray()
            ->all();
    }
}
