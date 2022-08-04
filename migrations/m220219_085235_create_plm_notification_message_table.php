<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_notification_message}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_notifications_list}}`
 */
class m220219_085235_create_plm_notification_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_notification_message}}', [
            'id' => $this->primaryKey(),
            'plm_notification_list_id' => $this->integer(),
            'message' => $this->text(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `plm_notification_list_id`
        $this->createIndex(
            '{{%idx-plm_notification_message-plm_notification_list_id}}',
            '{{%plm_notification_message}}',
            'plm_notification_list_id'
        );

        // add foreign key for table `{{%plm_notifications_list}}`
        $this->addForeignKey(
            '{{%fk-plm_notification_message-plm_notification_list_id}}',
            '{{%plm_notification_message}}',
            'plm_notification_list_id',
            '{{%plm_notifications_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_notifications_list}}`
        $this->dropForeignKey(
            '{{%fk-plm_notification_message-plm_notification_list_id}}',
            '{{%plm_notification_message}}'
        );

        // drops index for column `plm_notification_list_id`
        $this->dropIndex(
            '{{%idx-plm_notification_message-plm_notification_list_id}}',
            '{{%plm_notification_message}}'
        );

        $this->dropTable('{{%plm_notification_message}}');
    }
}
