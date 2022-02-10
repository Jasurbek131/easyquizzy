<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_group}}`
 */
class m220201_070146_add_equipment_group_id_column_to_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'equipment_group_id', $this->integer());

        // creates index for column `equipment_group_id`
        $this->createIndex(
            '{{%idx-products-equipment_group_id}}',
            '{{%products}}',
            'equipment_group_id'
        );

        // add foreign key for table `{{%equipment_group}}`
        $this->addForeignKey(
            '{{%fk-products-equipment_group_id}}',
            '{{%products}}',
            'equipment_group_id',
            '{{%equipment_group}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%equipment_group}}`
        $this->dropForeignKey(
            '{{%fk-products-equipment_group_id}}',
            '{{%products}}'
        );

        // drops index for column `equipment_group_id`
        $this->dropIndex(
            '{{%idx-products-equipment_group_id}}',
            '{{%products}}'
        );

        $this->dropColumn('{{%products}}', 'equipment_group_id');
    }
}
