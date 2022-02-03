<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delete_some_column_to_hr_employee}}`.
 */
class m220203_071926_create_delete_some_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee-hr_department_id}}',
            '{{%hr_employee}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_employee-hr_department_id}}',
            '{{%hr_employee}}'
        );

        // drops foreign key for table `{{%hr_positions}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee-hr_position_id}}',
            '{{%hr_employee}}'
        );

        // drops index for column `hr_position_id`
        $this->dropIndex(
            '{{%idx-hr_employee-hr_position_id}}',
            '{{%hr_employee}}'
        );

        $this->dropColumn('{{%hr_employee}}','hr_department_id');
        $this->dropColumn('{{%hr_employee}}','hr_position_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_employee-hr_department_id}}',
            '{{%hr_employee}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_employee-hr_department_id}}',
            '{{%hr_employee}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_position_id`
        $this->createIndex(
            '{{%idx-hr_employee-hr_position_id}}',
            '{{%hr_employee}}',
            'hr_position_id'
        );

        // add foreign key for table `{{%hr_positions}}`
        $this->addForeignKey(
            '{{%fk-hr_employee-hr_position_id}}',
            '{{%hr_employee}}',
            'hr_position_id',
            '{{%hr_positions}}',
            'id',
            'RESTRICT'
        );
        $this->addColumn('{{%hr_employee}}','hr_department_id',$this->integer());
        $this->addColumn('{{%hr_employee}}','hr_position_id',$this->integer());
    }
}
