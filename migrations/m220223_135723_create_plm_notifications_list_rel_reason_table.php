<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_notifications_list_rel_reason}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_notifications_list}}`
 * - `{{%reasons}}`
 */
class m220223_135723_create_plm_notifications_list_rel_reason_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_notifications_list_rel_reason}}', [
            'id' => $this->primaryKey(),
            'plm_notification_list_id' => $this->integer(),
            'reason_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `plm_notification_list_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list_rel_reason-plm_notification_list_id}}',
            '{{%plm_notifications_list_rel_reason}}',
            'plm_notification_list_id'
        );

        // add foreign key for table `{{%plm_notifications_list}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list_rel_reason-plm_notification_list_id}}',
            '{{%plm_notifications_list_rel_reason}}',
            'plm_notification_list_id',
            '{{%plm_notifications_list}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `reason_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list_rel_reason-reason_id}}',
            '{{%plm_notifications_list_rel_reason}}',
            'reason_id'
        );

        // add foreign key for table `{{%reasons}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list_rel_reason-reason_id}}',
            '{{%plm_notifications_list_rel_reason}}',
            'reason_id',
            '{{%reasons}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list_rel_reason-status_id}}',
            '{{%plm_notifications_list_rel_reason}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_notifications_list}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list_rel_reason-plm_notification_list_id}}',
            '{{%plm_notifications_list_rel_reason}}'
        );

        // drops index for column `plm_notification_list_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list_rel_reason-plm_notification_list_id}}',
            '{{%plm_notifications_list_rel_reason}}'
        );

        // drops foreign key for table `{{%reasons}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list_rel_reason-reason_id}}',
            '{{%plm_notifications_list_rel_reason}}'
        );

        // drops index for column `reason_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list_rel_reason-reason_id}}',
            '{{%plm_notifications_list_rel_reason}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list_rel_reason-status_id}}',
            '{{%plm_notifications_list_rel_reason}}'
        );

        $this->dropTable('{{%plm_notifications_list_rel_reason}}');
    }
}
