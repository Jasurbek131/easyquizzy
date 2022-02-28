<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_lifecycle}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%equipment_group}}`
 * - `{{%time_types_list}}`
 */
class m220127_154814_create_product_lifecycle_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_lifecycle}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'equipment_group_id' => $this->integer(),
            'lifecycle' => $this->integer(),
            'time_type_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-product_lifecycle-status_id}}',
            '{{%product_lifecycle}}',
            'status_id'
        );


        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-product_lifecycle-product_id}}',
            '{{%product_lifecycle}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-product_lifecycle-product_id}}',
            '{{%product_lifecycle}}',
            'product_id',
            '{{%products}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `equipment_group_id`
        $this->createIndex(
            '{{%idx-product_lifecycle-equipment_group_id}}',
            '{{%product_lifecycle}}',
            'equipment_group_id'
        );

        // add foreign key for table `{{%equipment_group}}`
        $this->addForeignKey(
            '{{%fk-product_lifecycle-equipment_group_id}}',
            '{{%product_lifecycle}}',
            'equipment_group_id',
            '{{%equipment_group}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `time_type_id`
        $this->createIndex(
            '{{%idx-product_lifecycle-time_type_id}}',
            '{{%product_lifecycle}}',
            'time_type_id'
        );

        // add foreign key for table `{{%time_types_list}}`
        $this->addForeignKey(
            '{{%fk-product_lifecycle-time_type_id}}',
            '{{%product_lifecycle}}',
            'time_type_id',
            '{{%time_types_list}}',
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
            '{{%fk-product_lifecycle-product_id}}',
            '{{%product_lifecycle}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-product_lifecycle-product_id}}',
            '{{%product_lifecycle}}'
        );

        // drops foreign key for table `{{%equipment_group}}`
        $this->dropForeignKey(
            '{{%fk-product_lifecycle-equipment_group_id}}',
            '{{%product_lifecycle}}'
        );

        // drops index for column `equipment_group_id`
        $this->dropIndex(
            '{{%idx-product_lifecycle-equipment_group_id}}',
            '{{%product_lifecycle}}'
        );

        // drops foreign key for table `{{%time_types_list}}`
        $this->dropForeignKey(
            '{{%fk-product_lifecycle-time_type_id}}',
            '{{%product_lifecycle}}'
        );

        // drops index for column `time_type_id`
        $this->dropIndex(
            '{{%idx-product_lifecycle-time_type_id}}',
            '{{%product_lifecycle}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-product_lifecycle-status_id}}',
            '{{%product_lifecycle}}'
        );

        $this->dropTable('{{%product_lifecycle}}');
    }
}
