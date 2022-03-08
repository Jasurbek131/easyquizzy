<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%references_product_group_rel_product}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%references_product_group}}`
 * - `{{%status_list}}`
 */
class m220215_084154_create_references_product_group_rel_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references_product_group_rel_product}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'product_group_id' => $this->integer(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-references_product_group_rel_product-product_id}}',
            '{{%references_product_group_rel_product}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-references_product_group_rel_product-product_id}}',
            '{{%references_product_group_rel_product}}',
            'product_id',
            '{{%products}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_group_id`
        $this->createIndex(
            '{{%idx-references_product_group_rel_product-product_group_id}}',
            '{{%references_product_group_rel_product}}',
            'product_group_id'
        );

        // add foreign key for table `{{%references_product_group}}`
        $this->addForeignKey(
            '{{%fk-references_product_group_rel_product-product_group_id}}',
            '{{%references_product_group_rel_product}}',
            'product_group_id',
            '{{%references_product_group}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-references_product_group_rel_product-product_id}}',
            '{{%references_product_group_rel_product}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-references_product_group_rel_product-product_id}}',
            '{{%references_product_group_rel_product}}'
        );

        // drops foreign key for table `{{%references_product_group}}`
        $this->dropForeignKey(
            '{{%fk-references_product_group_rel_product-product_group_id}}',
            '{{%references_product_group_rel_product}}'
        );

        // drops index for column `product_group_id`
        $this->dropIndex(
            '{{%idx-references_product_group_rel_product-product_group_id}}',
            '{{%references_product_group_rel_product}}'
        );

        $this->dropTable('{{%references_product_group_rel_product}}');
    }
}
