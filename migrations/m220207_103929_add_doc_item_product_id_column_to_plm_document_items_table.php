<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 */
class m220207_103929_add_doc_item_product_id_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'doc_item_product_id', $this->integer());

        // creates index for column `doc_item_product_id`
        $this->createIndex(
            '{{%idx-plm_document_items-doc_item_product_id}}',
            '{{%plm_document_items}}',
            'doc_item_product_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-doc_item_product_id}}',
            '{{%plm_document_items}}',
            'doc_item_product_id',
            '{{%plm_document_items}}',
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
            '{{%fk-plm_document_items-doc_item_product_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `doc_item_product_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-doc_item_product_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropColumn('{{%plm_document_items}}', 'doc_item_product_id');
    }
}
