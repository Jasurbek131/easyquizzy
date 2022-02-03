<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%equipment_group_relation_equipment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_group}}`
 * - `{{%equipments}}`
 */
class m220127_153832_create_equipment_group_relation_equipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%equipment_group_relation_equipment}}', [
            'id' => $this->primaryKey(),
            'equipment_group_id' => $this->integer(),
            'equipment_id' => $this->integer(),
            'work_order' => $this->integer(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-equipment_group_relation_equipment-status_id}}',
            '{{%equipment_group_relation_equipment}}',
            'status_id'
        );

        // creates index for column `equipment_group_id`
        $this->createIndex(
            '{{%idx-equipment_group_relation_equipment-equipment_group_id}}',
            '{{%equipment_group_relation_equipment}}',
            'equipment_group_id'
        );

        // add foreign key for table `{{%equipment_group}}`
        $this->addForeignKey(
            '{{%fk-equipment_group_relation_equipment-equipment_group_id}}',
            '{{%equipment_group_relation_equipment}}',
            'equipment_group_id',
            '{{%equipment_group}}',
            'id',
            'CASCADE'
        );

        // creates index for column `equipment_id`
        $this->createIndex(
            '{{%idx-equipment_group_relation_equipment-equipment_id}}',
            '{{%equipment_group_relation_equipment}}',
            'equipment_id'
        );

        // add foreign key for table `{{%equipments}}`
        $this->addForeignKey(
            '{{%fk-equipment_group_relation_equipment-equipment_id}}',
            '{{%equipment_group_relation_equipment}}',
            'equipment_id',
            '{{%equipments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%equipment_group}}`
        $this->dropForeignKey(
            '{{%fk-equipment_group_relation_equipment-equipment_group_id}}',
            '{{%equipment_group_relation_equipment}}'
        );

        // drops index for column `equipment_group_id`
        $this->dropIndex(
            '{{%idx-equipment_group_relation_equipment-equipment_group_id}}',
            '{{%equipment_group_relation_equipment}}'
        );

        // drops foreign key for table `{{%equipments}}`
        $this->dropForeignKey(
            '{{%fk-equipment_group_relation_equipment-equipment_id}}',
            '{{%equipment_group_relation_equipment}}'
        );

        // drops index for column `equipment_id`
        $this->dropIndex(
            '{{%idx-equipment_group_relation_equipment-equipment_id}}',
            '{{%equipment_group_relation_equipment}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-equipment_group_relation_equipment-status_id}}',
            '{{%equipment_group_relation_equipment}}'
        );

        $this->dropTable('{{%equipment_group_relation_equipment}}');
    }
}
