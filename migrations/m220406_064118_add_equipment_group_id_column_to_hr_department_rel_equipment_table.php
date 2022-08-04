<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_department_rel_equipment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_group}}`
 */
class m220406_064118_add_equipment_group_id_column_to_hr_department_rel_equipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_department_rel_equipment}}', 'equipment_group_id', $this->integer()->after('equipment_id'));

        // creates index for column `equipment_group_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_equipment-equipment_group_id}}',
            '{{%hr_department_rel_equipment}}',
            'equipment_group_id'
        );

        // add foreign key for table `{{%equipment_group}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_equipment-equipment_group_id}}',
            '{{%hr_department_rel_equipment}}',
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
            '{{%fk-hr_department_rel_equipment-equipment_group_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        // drops index for column `equipment_group_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_equipment-equipment_group_id}}',
            '{{%hr_department_rel_equipment}}'
        );

        $this->dropColumn('{{%hr_department_rel_equipment}}', 'equipment_group_id');
    }
}
