<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%telegram_history}}".
 *
 * @property int $chat_id
 * @property int $users_id
 * @property int $doc_id
 * @property string $callback_data
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TelegramHistoryItem[] $telegramHistoryItems
 */
class TelegramHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%telegram_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'users_id', 'doc_id', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['chat_id', 'users_id', 'doc_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['callback_data'], 'string', 'max' => 255],
            [['chat_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chat_id' => Yii::t('app', 'Chat ID'),
            'users_id' => Yii::t('app', 'Users ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'callback_data' => Yii::t('app', 'Callback Data'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramHistoryItems()
    {
        return $this->hasMany(TelegramHistoryItem::className(), [telegram_history_id => id]);
    }
}
