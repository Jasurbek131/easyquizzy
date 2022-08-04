<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%telegram_history_items}}".
 *
 * @property int $id
 * @property int $telegram_history_id
 * @property int $key
 * @property int $item_id
 * @property int $doc_id
 *
 * @property TelegramHistory $telegramHistory
 */
class TelegramHistoryItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%telegram_history_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_history_id', 'key', 'item_id', 'doc_id'], 'default', 'value' => null],
            [['telegram_history_id', 'key', 'item_id', 'doc_id'], 'integer'],
            [['telegram_history_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramHistory::className(), 'targetAttribute' => ['telegram_history_id' => 'chat_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'telegram_history_id' => Yii::t('app', 'Telegram History ID'),
            'key' => Yii::t('app', 'Key'),
            'item_id' => Yii::t('app', 'Item ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramHistory()
    {
        return $this->hasOne(TelegramHistory::className(), [id => telegram_history_id]);
    }
}
