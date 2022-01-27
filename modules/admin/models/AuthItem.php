<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 * @property string $category [varchar(64)]
 */
class AuthItem extends \yii\db\ActiveRecord
{
    public $perms = [];
    public $parents = [];

    public $new_permissions = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'unique'],
            [['name', 'type',], 'required'],
            ['category', 'required', 'when' => function ($model) {
                return $model->type == 2;
            }],

            ['name', function ($attribute, $params, $validator) {
                if ($this->type == 2) {
                    if (!strpos($this->$attribute, '/')) {
                        $this->addError($attribute, Yii::t('app', 'In name must be symbol "/" '));
                    }
                }
                if ($this->type == 1) {
                    if (strpos($this->$attribute, '/')) {
                        $this->addError($attribute, Yii::t('app', 'In name cannot be symbol "/" '));
                    }
                }
            }],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Roll nomi'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Sana'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'category' => Yii::t('app', 'Category'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->rule_name = null;
            $this->data = null;
            $this->created_at = $time = time();
            $this->updated_at = $time;
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    public function havePermittionsChecked($parent)
    {
        return AuthItemChild::find()->where(['parent' => $parent])->andWhere(['like', 'child', '%/%', false])->scalar();
    }

    public function checkPermitionChecked($child)
    {
        return AuthItemChild::find()->where(['parent' => $this->name])->andWhere(['child' => $child])->scalar();
    }

    public function haveParent($parent)
    {
        return AuthItemChild::find()->where(['parent' => $parent])->scalar();
    }

    public function getCategory($name = null)
    {
        if ($name == null) {
            $models = AuthItem::find()->where(['type' => 1])->orderBy(['name' => SORT_ASC])->all();
        } else {
            $models = AuthItem::find()->where(['type' => 1])->orderBy(['name' => SORT_ASC])->andWhere(['!=', 'name', $name])->all();
        }

        return ArrayHelper::map($models, 'name', 'name');
    }
    public function getParenList($name = null, $parent = true){
        if(!$parent){

            $model = ArrayHelper::index(AuthItemChild::find()
                ->where(['parent' => $name])
                ->andWhere(['not like', 'child', '%/%', false])
                ->asArray()
                ->all(), 'child');
        }else{
            $model = ArrayHelper::index(AuthItemChild::find()
                ->where(['child' => $name])
                ->andWhere(['not like', 'child', '%/%', false])
                ->asArray()
                ->all(), 'parent');
        }
        return $model;
    }

    public function getPermissions()
    {
        $models = AuthItem::find()->where(['type' => 2])->all();
        $permissions = ArrayHelper::map($models, 'name', 'name');
        return $permissions;
    }

    public static function getUserRoles() {
        return AuthItem::find()->select(['name as label', 'name as value'])->where(['type' => Item::TYPE_ROLE])->asArray()->all();
    }

    public static function getRoles($department=false)
    {
        $models = AuthItem::find()->where(['type' => Item::TYPE_ROLE]);
        if($department){
            $models = $models->andWhere(['department' => $department]);
        }
        $models = $models->all();
        $permissions = ArrayHelper::map($models, 'name', 'name');
        return $permissions;
    }
    public static function getRolesForWms()
    {
        $models = AuthItem::find()->where(['type' => 1])
            ->andWhere(['or',
                ['name'=>'storage-catalog-role'],
                ['name'=>'storage']
            ])
            ->all();
        $permissions = ArrayHelper::map($models, 'name', 'name');
        
        return $permissions;
    }
    public function getSelectedParents($name)
    {
        $models = AuthItemChild::find()->where(['parent' => $name])->all();
        $names = [];
        foreach ($models as $model) {
            array_push($names, $model->child);
        }
        return $names;
    }

    public static function getChilds($name)
    {
        $models = AuthItemChild::find()->where(['parent' => $name])->all();
        $names = [];
        foreach ($models as $model) {
            if (!strpos($model->child, '/'))
                array_push($names, $model->child);
        }
        return $names;
    }

    public static function getPermissionChild($id, $child = false)
    {
        if (!empty($id)) {
            $allIds = AuthItemChild::find()->where(['parent' => $id])->asArray()->all();
            foreach ($allIds as $name) {
                $res .= "<span class='p-1 badge badge-outline-success  mb-1 ml-1'>{$name["child"]}</span>";
            }
            return $allIds;
        }
        return null;
    }
    public static function getPermissionChildSecond($id)
    {
        if (!empty($id)) {
            $allIds = AuthItemChild::find()
                ->where(['parent' => $id])
                ->andWhere(['not like', 'child', '%/%', false])
                ->asArray()
                ->all();
            foreach ($allIds as $name) {
                $res .= "<span class='label-info label' style='font-size:13px;margin-left: 3px;padding: 3px'>{$name["child"]}</span>";
            }
            return $res;
        }
        return null;
    }


}
