<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee_rel_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%users}}`
 * - `{{%status_list}}`
 */
class m220128_110139_create_hr_employee_rel_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee_rel_users}}', [
            'id' => $this->primaryKey(),
            'hr_employee_id' => $this->integer(),
            'user_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_users-hr_employee_id}}',
            '{{%hr_employee_rel_users}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_users-hr_employee_id}}',
            '{{%hr_employee_rel_users}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_users-user_id}}',
            '{{%hr_employee_rel_users}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_users-user_id}}',
            '{{%hr_employee_rel_users}}',
            'user_id',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_users-status_id}}',
            '{{%hr_employee_rel_users}}',
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
            '{{%fk-hr_employee_rel_users-hr_employee_id}}',
            '{{%hr_employee_rel_users}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_users-hr_employee_id}}',
            '{{%hr_employee_rel_users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_rel_users-user_id}}',
            '{{%hr_employee_rel_users}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_users-user_id}}',
            '{{%hr_employee_rel_users}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_users-status_id}}',
            '{{%hr_employee_rel_users}}'
        );

        $this->dropTable('{{%hr_employee_rel_users}}');
    }
}
