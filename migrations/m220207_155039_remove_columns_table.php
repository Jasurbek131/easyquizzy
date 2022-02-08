<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220207_155039_remove_columns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-product_id}}',
            '{{%plm_document_items}}'
        );
        // drops index for column `repaired_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-product_id}}',
            '{{%plm_document_items}}'
        );
        $this->dropColumn('plm_document_items', 'product_id');
        $this->dropColumn('plm_document_items', 'qty');
        $this->dropColumn('plm_document_items', 'fact_qty');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
