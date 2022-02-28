<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_department_rel_equipment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%equipments}}`
 */
class m220203_050604_create_hr_department_rel_equipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_department_rel_equipment}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'equipment_id' => $this->integer(),
            'status_id' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_equipment-hr_department_id}}',
            '{{%hr_department_rel_equipment}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_equipment-hr_department_id}}',
            '{{%hr_department_rel_equipment}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `equipment_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_equipment-equipment_id}}',
            '{{%hr_department_rel_equipment}}',
            'equipment_id'
        );

        // add foreign key for table `{{%equipments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_equipment-equipment_id}}',
            '{{%hr_department_rel_equipment}}',
            'equipment_id',
            '{{%equipments}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_equipment-status_id}}',
            '{{%hr_department_rel_equipment}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_rel_equipment-hr_department_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_equipment-hr_department_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        // drops foreign key for table `{{%equipments}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_rel_equipment-equipment_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        // drops index for column `equipment_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_equipment-equipment_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_equipment-status_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        $this->dropTable('{{%hr_department_rel_equipment}}');
    }
}
