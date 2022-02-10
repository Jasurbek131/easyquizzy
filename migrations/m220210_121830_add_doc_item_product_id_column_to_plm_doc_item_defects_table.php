<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_doc_item_defects}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_doc_item_products}}`
 */
class m220210_121830_add_doc_item_product_id_column_to_plm_doc_item_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_doc_item_defects}}', 'doc_item_product_id', $this->integer());

        // creates index for column `doc_item_product_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_defects-doc_item_product_id}}',
            '{{%plm_doc_item_defects}}',
            'doc_item_product_id'
        );

        // add foreign key for table `{{%plm_doc_item_products}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_defects-doc_item_product_id}}',
            '{{%plm_doc_item_defects}}',
            'doc_item_product_id',
            '{{%plm_doc_item_products}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_doc_item_products}}`
        $this->dropForeignKey(
            '{{%fk-plm_doc_item_defects-doc_item_product_id}}',
            '{{%plm_doc_item_defects}}'
        );

        // drops index for column `doc_item_product_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_defects-doc_item_product_id}}',
            '{{%plm_doc_item_defects}}'
        );

        $this->dropColumn('{{%plm_doc_item_defects}}', 'doc_item_product_id');
    }
}
