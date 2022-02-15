<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_lifecycle}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%references_product_group}}`
 */
class m220215_075208_add_product_group_id_column_to_product_lifecycle_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_lifecycle}}', 'product_group_id', $this->integer());

        // creates index for column `product_group_id`
        $this->createIndex(
            '{{%idx-product_lifecycle-product_group_id}}',
            '{{%product_lifecycle}}',
            'product_group_id'
        );

        // add foreign key for table `{{%references_product_group}}`
        $this->addForeignKey(
            '{{%fk-product_lifecycle-product_group_id}}',
            '{{%product_lifecycle}}',
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
        // drops foreign key for table `{{%references_product_group}}`
        $this->dropForeignKey(
            '{{%fk-product_lifecycle-product_group_id}}',
            '{{%product_lifecycle}}'
        );

        // drops index for column `product_group_id`
        $this->dropIndex(
            '{{%idx-product_lifecycle-product_group_id}}',
            '{{%product_lifecycle}}'
        );

        $this->dropColumn('{{%product_lifecycle}}', 'product_group_id');
    }
}
