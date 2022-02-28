<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_doc_item_products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 * - `{{%products}}`
 */
class m220207_100335_create_plm_doc_item_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_doc_item_products}}', [
            'id' => $this->primaryKey(),
            'document_item_id' => $this->integer(),
            'product_id' => $this->integer(),
            'qty' => $this->integer()->defaultValue(0),
            'fact_qty' => $this->integer()->defaultValue(0)
        ]);

        // creates index for column `document_item_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_products-document_item_id}}',
            '{{%plm_doc_item_products}}',
            'document_item_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_products-document_item_id}}',
            '{{%plm_doc_item_products}}',
            'document_item_id',
            '{{%plm_document_items}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_products-product_id}}',
            '{{%plm_doc_item_products}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_products-product_id}}',
            '{{%plm_doc_item_products}}',
            'product_id',
            '{{%products}}',
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
            '{{%fk-plm_doc_item_products-document_item_id}}',
            '{{%plm_doc_item_products}}'
        );

        // drops index for column `document_item_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_products-document_item_id}}',
            '{{%plm_doc_item_products}}'
        );

        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-plm_doc_item_products-product_id}}',
            '{{%plm_doc_item_products}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_products-product_id}}',
            '{{%plm_doc_item_products}}'
        );

        $this->dropTable('{{%plm_doc_item_products}}');
    }
}
