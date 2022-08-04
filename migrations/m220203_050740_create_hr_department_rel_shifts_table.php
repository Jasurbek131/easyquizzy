<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_department_rel_shifts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%shifts}}`
 */
class m220203_050740_create_hr_department_rel_shifts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_department_rel_shifts}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'shift_id' => $this->integer(),
            'status_id' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_shifts-hr_department_id}}',
            '{{%hr_department_rel_shifts}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_shifts-hr_department_id}}',
            '{{%hr_department_rel_shifts}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `shift_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_shifts-shift_id}}',
            '{{%hr_department_rel_shifts}}',
            'shift_id'
        );

        // add foreign key for table `{{%shifts}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_shifts-shift_id}}',
            '{{%hr_department_rel_shifts}}',
            'shift_id',
            '{{%shifts}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_shifts-status_id}}',
            '{{%hr_department_rel_shifts}}',
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
            '{{%fk-hr_department_rel_shifts-hr_department_id}}',
            '{{%hr_department_rel_shifts}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_shifts-hr_department_id}}',
            '{{%hr_department_rel_shifts}}'
        );

        // drops foreign key for table `{{%shifts}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_rel_shifts-shift_id}}',
            '{{%hr_department_rel_shifts}}'
        );

        // drops index for column `shift_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_shifts-shift_id}}',
            '{{%hr_department_rel_shifts}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_shifts-status_id}}',
            '{{%hr_department_rel_shifts}}'
        );

        $this->dropTable('{{%hr_department_rel_shifts}}');
    }
}
