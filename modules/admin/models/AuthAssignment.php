<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    public $department;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['department'], 'string', 'max' => 50],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Item Name'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
    public static function getUserRoles($id){
        $data = self::find()->select('item_name')->where(['user_id' => $id])->asArray()->all();
        if(!empty($data)){
            return $data;
        }else{
            return '';
        }
    }

    /**
     * @param $id
     * @return AuthAssignment[]|array|yii\db\ActiveRecord[]
     */
    public static function getUserRolesAll($id){
        return self::find()->select('item_name')
            ->with([
                'itemName' => function ($q) {
                    $q->from(['au' => 'auth_item'])->where(['type' => 1])->with([
                        'authItemChildren'
                    ]);
                },
            ])
            ->where(['user_id' => $id])->asArray()->all();
    }
}
