<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_notification_rel_defect}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_notifications_list}}`
 * - `{{%defects}}`
 */
class m220217_110519_create_plm_notification_rel_defect_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_notification_rel_defect}}', [
            'id' => $this->primaryKey(),
            'plm_notification_list_id' => $this->integer(),
            'defect_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `plm_notification_list_id`
        $this->createIndex(
            '{{%idx-plm_notification_rel_defect-plm_notification_list_id}}',
            '{{%plm_notification_rel_defect}}',
            'plm_notification_list_id'
        );

        // add foreign key for table `{{%plm_notifications_list}}`
        $this->addForeignKey(
            '{{%fk-plm_notification_rel_defect-plm_notification_list_id}}',
            '{{%plm_notification_rel_defect}}',
            'plm_notification_list_id',
            '{{%plm_notifications_list}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `defect_id`
        $this->createIndex(
            '{{%idx-plm_notification_rel_defect-defect_id}}',
            '{{%plm_notification_rel_defect}}',
            'defect_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_notification_rel_defect-defect_id}}',
            '{{%plm_notification_rel_defect}}',
            'defect_id',
            '{{%defects}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_notification_rel_defect-status_id}}',
            '{{%plm_notification_rel_defect}}',
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
            '{{%fk-plm_notification_rel_defect-plm_notification_list_id}}',
            '{{%plm_notification_rel_defect}}'
        );

        // drops index for column `plm_notification_list_id`
        $this->dropIndex(
            '{{%idx-plm_notification_rel_defect-plm_notification_list_id}}',
            '{{%plm_notification_rel_defect}}'
        );

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_notification_rel_defect-defect_id}}',
            '{{%plm_notification_rel_defect}}'
        );

        // drops index for column `defect_id`
        $this->dropIndex(
            '{{%idx-plm_notification_rel_defect-defect_id}}',
            '{{%plm_notification_rel_defect}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-plm_notification_rel_defect-status_id}}',
            '{{%plm_notification_rel_defect}}'
        );

        $this->dropTable('{{%plm_notification_rel_defect}}');
    }
}
