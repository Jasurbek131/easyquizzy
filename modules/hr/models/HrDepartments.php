<?php

namespace app\modules\hr\models;

use app\modules\references\models\Shifts;
use kartik\tree\models\Tree;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_departments".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ru
 * @property string $token
 * @property int $status_id
 * @property int $parent_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property float $value
 * @property HrEmployee[] $hrEmployees
 */
class HrDepartments extends BaseModel
{
    const ADMINISTRATION_TYPE = 1; // ma'muriyat bo'limlari uchun
    const PRODUCTION_TYPE = 2;     // ishlab chiqarish qismi uchun
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at','parent_id', 'value'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'value'], 'integer'],
            [['status_id'],'default','value' => \app\models\BaseModel::STATUS_ACTIVE],
            [['name', 'name_ru', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'token' => Yii::t('app', 'Token'),
            'value' => Yii::t('app', 'Value'),
            'parent_id' => Yii::t('app', 'Hr Parent ID'),
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
    public function getDepartments()
    {
        return $this->hasMany(HrDepartments::className(), ['parent_id' => 'id']);
    }

    public function getShifts() {
        return $this->hasMany(HrDepartmentRelShifts::className(), ['hr_department_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['hr_department_id' => 'id']);
    }

    /**
     * @param bool $isArray
     * @param int $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList( $isArray = false, $parent_id = null) {
        $list = self::find()->select(['id as value', 'name as label'])
            ->andFilterWhere([
                "parent_id" => $parent_id
            ])
            ->asArray()
            ->all();
        if ($isArray)
            return $list;

        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @param bool $isArray
     * @return array|\yii\db\ActiveRecord[]
     * Tashkilotlar ro'yxatini qaytaradi [parent = null]
     */
    public static function getOrganisationList( $isArray = false) {
        $list = self::find()->select(['id as value', 'name as label'])
            ->where(["IS", "parent_id", new Expression("NULL")])
            ->andFilterWhere(['id' => UsersRelationHrDepartments::getRootByUser()])
            ->asArray()
            ->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @param null $parent_id
     * @param null $dep
     * @param bool $isJson
     * @param array $user_departments
     * @return array|string
     */
    public static function getTreeViewHtmlForm($parent_id = null, $dep = null, $user_departments = []) //TODO optimallashtirish kerak
    {
        $items = self::find()
            ->where(['parent_id' => $parent_id])
            ->andWhere(['!=','status_id',\app\models\BaseModel::STATUS_INACTIVE])
            ->andFilterWhere(['id' => $user_departments])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();

        $tree = "";
        foreach ($items as $item)
            if ($item['id'] == $dep)
                $tree = $tree . "<ul><li value='{$item['id']}'  data-jstree='{ \"selected\" : true }'>{$item['name']}" . self::getTreeViewHtmlForm($item['id']) . "</li></ul>";
            else
                $tree = $tree . "<ul><li value='{$item['id']}'  data-jstree='{  }'>{$item['name']}" . self::getTreeViewHtmlForm($item['id']) . "</li></ul>";

        return $tree;
    }

    /**
     * @param array $parent_id
     * @return array
     * Tashkilotga tegishli bo'limlar
     */
    public static function getChilds($parent_id  = []) //TODO optimallashtirish kerak
    {
        $items = self::find()
            ->where(['parent_id' => $parent_id])
            ->andWhere(['!=','status_id',\app\models\BaseModel::STATUS_INACTIVE])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item['id'];
            $ids = array_merge($ids, self::getChilds($item['id']));
        }
        return $ids;
    }
}
