<?php

namespace app\modules\references\models;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
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
 * @property Reasons[] $reasonss
 */
class Categories extends BaseModel
{
    const PLANNED_TYPE  = 1; // rejali to'xtalish turi
    const UNPLANNED_TYPE  = 2; // rejasiz to'xtalish turi

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
    public function getReasonss()
    {
        return $this->hasMany(Reasons::className(), ['category_id' => 'id']);
    }
    public static function getCategoryTypeList($key = null){
        $result = [
            self::PLANNED_TYPE => Yii::t('app','Planned Type'),
            self::UNPLANNED_TYPE => Yii::t('app','Unplanned Type'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    public static function  getList()
    {
        $query = self::find()->select(['id','name_uz'])->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])->asArray()->all();
        if(!empty($query)){
            return ArrayHelper::map($query, 'id', 'name');
        }
    }

}
