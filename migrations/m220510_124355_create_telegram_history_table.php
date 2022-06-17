<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_history}}`.
 *
 * Handles the creation of table `{{%telegram_history_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%telegram_history}}`
 */
class m220510_124355_create_telegram_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%telegram_history}}', [
            'chat_id' => $this->bigInteger()->notNull(),
            'users_id' => $this->integer(),
            'doc_id' => $this->integer(),
            'callback_data' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->addPrimaryKey('chat_id_pk', '{{%telegram_history}}', ['chat_id']);
        $this->createTable('{{%telegram_history_items}}', [
            'id' => $this->primaryKey(),
            'telegram_history_id' => $this->bigInteger(),
            'key' => $this->smallInteger(),
            'item_id' => $this->integer(),
            'doc_id' => $this->integer(),
        ]);

        // creates index for column `telegram_history_id`
        $this->createIndex(
            '{{%idx-telegram_history_items-telegram_history_id}}',
            '{{%telegram_history_items}}',
            'telegram_history_id'
        );

        // add foreign key for table `{{%telegram_history}}`
        $this->addForeignKey(
            '{{%fk-telegram_history_items-telegram_history_id}}',
            '{{%telegram_history_items}}',
            'telegram_history_id',
            '{{%telegram_history}}',
            'chat_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%telegram_history}}`
        $this->dropForeignKey(
            '{{%fk-telegram_history_items-telegram_history_id}}',
            '{{%telegram_history_items}}'
        );

        // drops index for column `telegram_history_id`
        $this->dropIndex(
            '{{%idx-telegram_history_items-telegram_history_id}}',
            '{{%telegram_history_items}}'
        );

        $this->dropTable('{{%telegram_history_items}}');
        $this->dropTable('{{%telegram_history}}');
    }
}
