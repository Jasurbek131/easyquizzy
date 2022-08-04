<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee_rel_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_positions}}`
 */
class m220203_045126_create_hr_employee_rel_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee_rel_position}}', [
            'id' => $this->primaryKey(),
            'hr_employee_id' => $this->integer(),
            'hr_department_id' => $this->integer(),
            'hr_position_id' => $this->integer(),
            'begin_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'status_id' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

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

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_position-hr_department_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_position-hr_department_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_position_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_position-hr_position_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_position_id'
        );

        // add foreign key for table `{{%hr_positions}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_position-hr_position_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_position_id',
            '{{%hr_positions}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_position-status_id}}',
            '{{%hr_employee_rel_position}}',
            'status_id'
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

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_rel_position-hr_department_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_position-hr_department_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drops foreign key for table `{{%hr_positions}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_rel_position-hr_position_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drops index for column `hr_position_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_position-hr_position_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_position-status_id}}',
            '{{%hr_employee_rel_position}}'
        );
        $this->dropTable('{{%hr_employee_rel_position}}');
    }
}
