<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_processing_time}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220128_092538_create_plm_processing_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_processing_time}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'begin_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'add_info' => $this->text(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-plm_processing_time-doc_id}}',
            '{{%plm_processing_time}}',
            'doc_id'
        );

        // add foreign key for table `{{%plm_documents}}`
        $this->addForeignKey(
            '{{%fk-plm_processing_time-doc_id}}',
            '{{%plm_processing_time}}',
            'doc_id',
            '{{%plm_documents}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_processing_time-status_id}}',
            '{{%plm_processing_time}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-plm_processing_time-status_id}}',
            '{{%plm_processing_time}}'
        );

        $this->dropTable('{{%plm_processing_time}}');
    }
}
