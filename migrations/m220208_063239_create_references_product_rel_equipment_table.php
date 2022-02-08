<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%references_product_rel_equipment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%equipments}}`
 * - `{{%status_list}}`
 */
class m220208_063239_create_references_product_rel_equipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references_product_rel_equipment}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'equipment_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-references_product_rel_equipment-product_id}}',
            '{{%references_product_rel_equipment}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-references_product_rel_equipment-product_id}}',
            '{{%references_product_rel_equipment}}',
            'product_id',
            '{{%products}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `equipment_id`
        $this->createIndex(
            '{{%idx-references_product_rel_equipment-equipment_id}}',
            '{{%references_product_rel_equipment}}',
            'equipment_id'
        );

        // add foreign key for table `{{%equipments}}`
        $this->addForeignKey(
            '{{%fk-references_product_rel_equipment-equipment_id}}',
            '{{%references_product_rel_equipment}}',
            'equipment_id',
            '{{%equipments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-references_product_rel_equipment-status_id}}',
            '{{%references_product_rel_equipment}}',
            'status_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-references_product_rel_equipment-product_id}}',
            '{{%references_product_rel_equipment}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-references_product_rel_equipment-product_id}}',
            '{{%references_product_rel_equipment}}'
        );

        // drops foreign key for table `{{%equipments}}`
        $this->dropForeignKey(
            '{{%fk-references_product_rel_equipment-equipment_id}}',
            '{{%references_product_rel_equipment}}'
        );

        // drops index for column `equipment_id`
        $this->dropIndex(
            '{{%idx-references_product_rel_equipment-equipment_id}}',
            '{{%references_product_rel_equipment}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-references_product_rel_equipment-status_id}}',
            '{{%references_product_rel_equipment}}'
        );

        $this->dropTable('{{%references_product_rel_equipment}}');
    }
}
