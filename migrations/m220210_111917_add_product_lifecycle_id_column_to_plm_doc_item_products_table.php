<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_doc_item_products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product_lifecycle}}`
 */
class m220210_111917_add_product_lifecycle_id_column_to_plm_doc_item_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_doc_item_products}}', 'product_lifecycle_id', $this->integer());

        // creates index for column `product_lifecycle_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_products-product_lifecycle_id}}',
            '{{%plm_doc_item_products}}',
            'product_lifecycle_id'
        );

        // add foreign key for table `{{%product_lifecycle}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_products-product_lifecycle_id}}',
            '{{%plm_doc_item_products}}',
            'product_lifecycle_id',
            '{{%product_lifecycle}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%product_lifecycle}}`
        $this->dropForeignKey(
            '{{%fk-plm_doc_item_products-product_lifecycle_id}}',
            '{{%plm_doc_item_products}}'
        );

        // drops index for column `product_lifecycle_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_products-product_lifecycle_id}}',
            '{{%plm_doc_item_products}}'
        );

        $this->dropColumn('{{%plm_doc_item_products}}', 'product_lifecycle_id');
    }
}
