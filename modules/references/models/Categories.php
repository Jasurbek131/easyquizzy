<?php

namespace app\modules\references\models;
use app\modules\hr\models\HrDepartments;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $token
 * @property int $type
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Reasons[] $reasonss
 */
class Categories extends BaseModel
{
    const PLANNED_TYPE  = 1; // rejali to'xtalish turi
    const UNPLANNED_TYPE  = 2; // rejasiz to'xtalish turi

    const TOKEN_WORKING_TIME = "WORKING_TIME";
    const TOKEN_REPAIRED = "REPAIRED";
    const TOKEN_INVALID = "INVALID";
    const TOKEN_PLANNED = "PLANNED";
    const TOKEN_UNPLANNED = "UNPLANNED";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['type', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz', 'name_ru', 'token'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if($this->type == self::PLANNED_TYPE)
            $this->token = self::TOKEN_PLANNED;

        if($this->type == self::UNPLANNED_TYPE)
            $this->token = self::TOKEN_UNPLANNED;

        return parent::beforeSave($insert);
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
            'type' => Yii::t('app', 'Type'),
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
    public function getReasons()
    {
        return $this->hasMany(Reasons::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getCategoryTypeList($key = null)
    {
        $result = [
            self::PLANNED_TYPE => Yii::t('app','Planned Type'),
            self::UNPLANNED_TYPE => Yii::t('app','Unplanned Type'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param bool $isMap
     * @param null $type
     * @return array
     */
    public static function  getList($isMap = false, $type = null): array
    {
        $language = Yii::$app->language;
        $query = self::find()
            ->select([
                'id',
                'id as value',
                "name_{$language} as label",
                "name_{$language} as name",
            ])
            ->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])
            ->andFilterWhere(['type' => $type])
            ->asArray()->all();
        if(!empty($query) && $isMap){
            return ArrayHelper::map($query, 'id', 'name');
        }
        return  $query;
    }

    /**
     * @param null $token
     * @return int|null
     */
    public static function getIdByToken($token = null){
        if($token && $result = self::findOne(['token' => $token])){
            return $result->id ?? null;
        }
        return null;
    }
}
