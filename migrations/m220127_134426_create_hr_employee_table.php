<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_positions}}`
 */
class m220127_134426_create_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(255),
            'lastname' => $this->string(255),
            'fathername' => $this->string(255),
            'phone_number' => $this->string(30),
            'email' => $this->string(255),
            'hr_department_id' => $this->integer(),
            'hr_position_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

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
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_employee-status_id}}',
            '{{%hr_employee}}',
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

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_employee-status_id}}',
            '{{%hr_employee}}'
        );

        $this->dropTable('{{%hr_employee}}');
    }
}
