<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_unplanned_stop}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220128_093357_create_plm_unplanned_stop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_unplanned_stop}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'begin_date' => $this->datetime(),
            'end_time' => $this->datetime(),
            'add_info' => $this->text(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-plm_unplanned_stop-doc_id}}',
            '{{%plm_unplanned_stop}}',
            'doc_id'
        );

        // add foreign key for table `{{%plm_documents}}`
        $this->addForeignKey(
            '{{%fk-plm_unplanned_stop-doc_id}}',
            '{{%plm_unplanned_stop}}',
            'doc_id',
            '{{%plm_documents}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_unplanned_stop-status_id}}',
            '{{%plm_unplanned_stop}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $q = $this->db->createCommand("SELECT EXISTS (SELECT FROM pg_tables WHERE schemaname = 'public' AND tablename  = 'plm_unplanned_stop' );")->queryScalar();
        if ($q) {
            // drops foreign key for table `{{%plm_documents}}`
            $this->dropForeignKey(
                '{{%fk-plm_unplanned_stop-doc_id}}',
                '{{%plm_unplanned_stop}}'
            );

            // drops index for column `doc_id`
            $this->dropIndex(
                '{{%idx-plm_unplanned_stop-doc_id}}',
                '{{%plm_unplanned_stop}}'
            );
            $this->dropIndex(
                '{{%idx-plm_unplanned_stop-status_id}}',
                '{{%plm_unplanned_stop}}'
            );

            $this->dropTable('{{%plm_unplanned_stop}}');
        }
    }
}
