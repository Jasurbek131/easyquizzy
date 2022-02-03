<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee_rel_positon}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m220203_054609_add_hr_employee_id_column_to_hr_employee_rel_positon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee_rel_position}}', 'hr_employee_id', $this->integer());

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_position-hr_employee_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_position-hr_employee_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_rel_position-hr_employee_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_position-hr_employee_id}}',
            '{{%hr_employee_rel_position}}'
        );

        $this->dropColumn('{{%hr_employee_rel_position}}', 'hr_employee_id');
    }
}
