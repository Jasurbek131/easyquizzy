<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property string $name_for_user
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $role_type
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
    /**
     * @var array
     */
    public $perms = [];

    /**
     * @var array
     */
    public $parents = [];

    /**
     * @var array
     */
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

            ['name', function ($attribute) {
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
            [['type', 'created_at', 'updated_at', 'role_type'], 'integer'],
            [['description', 'data', 'name_for_user'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Roll nomi'),
            'name_for_user' => Yii::t('app', 'Foydalanuvchi uchun roll nomi'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Sana'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'category' => Yii::t('app', 'Category'),
            'role_type' => Yii::t('app', 'Role type'),
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
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * @param $child
     * @return false|int|string|null
     */
    public function checkPermitionChecked($child)
    {
        return AuthItemChild::find()->where(['parent' => $this->name])->andWhere(['child' => $child])->scalar();
    }

    /**
     * @param null $name
     * @return array
     */
    public function getCategory($name = null)
    {
        if ($name == null)
            $models = AuthItem::find()->where(['type' => 1])->orderBy(['name' => SORT_ASC])->all();
        else
            $models = AuthItem::find()->where(['type' => 1])->orderBy(['name' => SORT_ASC])->andWhere(['!=', 'name', $name])->all();

        return ArrayHelper::map($models, 'name', 'name');
    }

    /**
     * @param null $name
     * @param bool $parent
     * @return array
     */
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

    /**
     * @param bool $isMap
     * @return array
     */
    public static function getRoles($isMap = false)
    {
        $models = AuthItem::find()
            ->where(['type' => Item::TYPE_ROLE])
            ->asArray()
            ->orderBy([
                "role_type" => SORT_ASC,
                "name" => SORT_DESC
            ])
            ->all();
        if (!empty($models) && $isMap){
            $models = ArrayHelper::index($models, 'name','role_type');
            if ($models){
                foreach ($models as $key => $model){
                    $models[$key] =  ArrayHelper::map($model, 'name', 'name_for_user');
                }
            }
            return $models;
        }
        return [];
    }

    /**
     * @param $id
     * @param bool $child
     * @return array|\yii\db\ActiveRecord[]|null
     */
    public static function getPermissionChild($id, $child = false)
    {
        if (!empty($id))
            return AuthItemChild::find()->where(['parent' => $id])->asArray()->all();

        return null;
    }

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function getRoleType($key = null)
    {
        $data = [
            BaseModel::CREATE =>  Yii::t('app', 'Create'),
            BaseModel::UPDATE =>  Yii::t('app', 'Update'),
            BaseModel::VIEW =>  Yii::t('app', 'View'),
            BaseModel::DELETE =>  Yii::t('app', 'Delete'),
        ];
        if ($key)
            return  $data[$key] ?? "";
        return $data;
    }
}
