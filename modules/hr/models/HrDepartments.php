<?php

namespace app\modules\hr\models;

use kartik\tree\models\Tree;
use Yii;
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
 *
 * @property HrEmployee[] $hrEmployees
 */
class HrDepartments extends Tree
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
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at','parent_id'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at','parent_id'], 'integer'],
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
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['hr_department_id' => 'id']);
    }

    public static function getList($key = null, $isArray = false) {
        $list = self::find()->select(['id as value', 'name as label'])->asArray()->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    public static function getParentList() {
        $list = self::find()
            ->select(['id', 'name'])
//            ->andFilterWhere(['=','parent_id',$id])
            ->asArray()
            ->orderBy(['id' => SORT_ASC])
            ->all();
       /* if ($isArray) {
            return $list;
        }*/
        return ArrayHelper::map($list, 'id', 'name');
    }

    public static function getTreeViewHtmlForm($parent_id = null,$dep = null, $isJson = false){
        $items = self::find()
            ->where(['parent_id' => $parent_id])
            ->andWhere(['!=','status_id',\app\models\BaseModel::STATUS_INACTIVE])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();
        if ($isJson) {
            $tree = [];
            foreach ($items as $item) {
                $tree[] = [
                    'id'            =>  $item['id'],
                    'text'          =>  $item['name'],
                    'state'         =>  [
                        'opened'    =>  $item['id'] != $dep,
                        'selected'  =>  $item['id'] == $dep,
                    ],
                    'children'      =>  self::getTreeViewHtmlForm($item['id'], null, true),
                    'li_attr'       =>  [
                        'value'     => $item['id'],
                    ],
                ];
            }
        } else {
            $tree = "";
            foreach ($items as $item) {
                if ($item['id'] == $dep) {
                    $tree = $tree . "<ul><li value='{$item['id']}'  data-jstree='{ \"selected\" : true }'>{$item['name']}" . self::getTreeViewHtmlForm($item['id']) . "</li></ul>";
                } else {
                    $tree = $tree . "<ul><li value='{$item['id']}'  data-jstree='{  }'>{$item['name']}" . self::getTreeViewHtmlForm($item['id']) . "</li></ul>";
                }
            }
        }

        return $tree;
    }
}
