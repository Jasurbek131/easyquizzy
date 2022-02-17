<?php

namespace app\modules\references\models;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartmentRelDefects;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocumentItems;
use app\widgets\Language;
use Yii;
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
 */
class Defects extends BaseModel
{
    const REPAIRED_TYPE  = 1; // ta'mirlangan ishlar uchun tur
    const INVALID_TYPE  = 2;   // yaroqsiz ishlar uchun tur
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
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartmentRelDefects()
    {
        return $this->hasMany(HrDepartmentRelDefects::className(), ['defect_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::className(), ['defect_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasMany(PlmDocumentItems::className(), ['defect_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDefectTypeList($key = null){
        $result = [
            self::REPAIRED_TYPE => Yii::t('app','Repaired Type'),
            self::INVALID_TYPE => Yii::t('app','Invalid Type'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public static function getList($key = null, $isArray = false) {
        $language = Language::widget();
        $list = self::find()->asArray()->select(['id as id', "{$language} as name"])->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'id', 'name');
    }

    /**
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListByType($type)
    {
        $language = Yii::$app->language;

        return Defects::find()
            ->select([
                'id as value',
                "name_{$language} as label",
                "SUM(0) as count"
            ])->where(['status_id' => BaseModel::STATUS_ACTIVE])
            ->andWhere(['type' => $type])
            ->groupBy('id')
            ->asArray()
            ->all();
    }
}
