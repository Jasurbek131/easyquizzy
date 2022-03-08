<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use app\models\Users;
use app\widgets\Language;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "redirect_url_list".
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $url
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Users[] $userss
 */
class RedirectUrlList extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'redirect_url_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name_uz', 'name_ru', 'url'], 'string', 'max' => 255],
            [['name_uz', 'url'], 'required'],
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
            'url' => Yii::t('app', 'Url'),
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
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['redirect_url_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->status_id = BaseModel::STATUS_ACTIVE;

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $isMap
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public static function getList($isMap = false) {
        $list = self::find()->select(['id', sprintf("%s as name", Language::widget())])->asArray()->all();
        if ($isMap)
            return ArrayHelper::map($list, 'id', 'name');

        return $list;
    }
}
