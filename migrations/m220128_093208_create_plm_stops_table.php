<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_stops}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220128_093208_create_plm_stops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_stops}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'begin_date' => $this->datetime(),
            'end_time' => $this->datetime(),
            'stopping_type' => $this->smallInteger(),
            'add_info' => $this->text(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-plm_stops-doc_id}}',
            '{{%plm_stops}}',
            'doc_id'
        );

        // add foreign key for table `{{%plm_documents}}`
        $this->addForeignKey(
            '{{%fk-plm_stops-doc_id}}',
            '{{%plm_stops}}',
            'doc_id',
            '{{%plm_documents}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_stops-status_id}}',
            '{{%plm_stops}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_documents}}`
        $this->dropForeignKey(
            '{{%fk-plm_stops-doc_id}}',
            '{{%plm_stops}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-plm_stops-doc_id}}',
            '{{%plm_stops}}'
        );
        $this->dropIndex(
            '{{%idx-plm_stops-status_id}}',
            '{{%plm_stops}}'
        );

        $this->dropTable('{{%plm_stops}}');
    }
}
