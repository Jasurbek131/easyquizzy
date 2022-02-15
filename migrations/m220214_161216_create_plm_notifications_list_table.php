<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_notifications_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 * - `{{%defects}}`
 * - `{{%reasons}}`
 */
class m220214_161216_create_plm_notifications_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_notifications_list}}', [
            'id' => $this->primaryKey(),
            'plm_doc_item_id' => $this->integer(),
            'begin_time' => $this->datetime(),
            'end_time' => $this->datetime(),
            'defect_id' => $this->integer(),
            'defect_type_id' => $this->integer(),
            'defect_count' => $this->integer(),
            'reason_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `plm_doc_item_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-plm_doc_item_id}}',
            '{{%plm_notifications_list}}',
            'plm_doc_item_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-plm_doc_item_id}}',
            '{{%plm_notifications_list}}',
            'plm_doc_item_id',
            '{{%plm_document_items}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `defect_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}',
            'defect_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}',
            'defect_id',
            '{{%defects}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `reason_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}',
            'reason_id'
        );

        // add foreign key for table `{{%reasons}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}',
            'reason_id',
            '{{%reasons}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_document_items}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-plm_doc_item_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `plm_doc_item_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-plm_doc_item_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `defect_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops foreign key for table `{{%reasons}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `reason_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}'
        );

        $this->dropTable('{{%plm_notifications_list}}');
    }
}
