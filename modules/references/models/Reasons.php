<?php

namespace app\modules\references\models;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartments;
use app\modules\plm\models\PlmDocumentItems;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "reasons".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property int $category_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Categories $categories
 * @property PlmDocumentItems[] $plmDocumentItems
 */
class Reasons extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reasons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['category_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz', 'name_ru'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'category_id' => Yii::t('app', 'Category ID'),
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
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasMany(PlmDocumentItems::class, ['reason_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @param null $category_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCategoryList($category_id = null){
        $query = self::find()
            ->select([
                "id",
                "name_uz AS name",
            ])
            ->where(['category_id' => $category_id])
            ->asArray()
            ->all();
        if(!empty($query)){
            return $query;
        }
        return $query;
    }

    /**
     * @param bool $isMap
     * @param null $token
     * @return array
     */
    public static function  getList($isMap = false, $token = null): array
    {
        $language = Yii::$app->language;
        $query = self::find()
            ->alias("r")
            ->select([
                'r.id',
                'r.id as value',
                "r.name_{$language} as label",
                "r.name_{$language} as name",
            ])
            ->leftJoin(["c" => "categories"], "r.category_id = c.id")
            ->where([
                'r.status_id' => \app\models\BaseModel::STATUS_ACTIVE,
            ])
            ->andFilterWhere([
                "c.token" => $token
            ])
            ->asArray()
            ->all();
        if(!empty($query) && $isMap){
            return ArrayHelper::map($query, 'id', 'name');
        }
        return  $query;
    }
}
