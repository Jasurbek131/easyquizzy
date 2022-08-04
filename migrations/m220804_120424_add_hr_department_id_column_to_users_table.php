<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m220804_120424_add_hr_department_id_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'hr_department_id', $this->integer());

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-users-hr_department_id}}',
            '{{%users}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-users-hr_department_id}}',
            '{{%users}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-users-hr_department_id}}',
            '{{%users}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-users-hr_department_id}}',
            '{{%users}}'
        );

        $this->dropColumn('{{%users}}', 'hr_department_id');
    }
}
