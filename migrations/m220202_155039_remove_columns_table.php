<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220202_155039_remove_columns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}'
        );
        // drops index for column `repaired_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}'
        );
        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}'
        );
        // drops index for column `scrapped_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}'
        );
        $this->dropColumn('plm_document_items', 'repaired_id');
        $this->dropColumn('plm_document_items', 'scrapped_id');




        // drops foreign key for table `{{%plm_documents}}`
        $this->dropForeignKey(
            '{{%fk-plm_scheduled_stop-doc_id}}',
            '{{%plm_stops}}'
        );
        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-plm_scheduled_stop-doc_id}}',
            '{{%plm_stops}}'
        );
        $this->dropColumn('plm_stops', 'doc_id');





        // drops foreign key for table `{{%plm_documents}}`
        $this->dropForeignKey(
            '{{%fk-plm_processing_time-doc_id}}',
            '{{%plm_processing_time}}'
        );
        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-plm_processing_time-doc_id}}',
            '{{%plm_processing_time}}'
        );
        $this->dropColumn('plm_processing_time', 'doc_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
