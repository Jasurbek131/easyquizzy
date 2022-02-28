<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_relation_hr_departments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%hr_departments}}`
 */
class m220201_080836_create_users_relation_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_relation_hr_departments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'hr_department_id' => $this->integer(),
            'is_root' => $this->boolean()->defaultValue(false),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-users_relation_hr_departments-user_id}}',
            '{{%users_relation_hr_departments}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users_relation_hr_departments-user_id}}',
            '{{%users_relation_hr_departments}}',
            'user_id',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-users_relation_hr_departments-hr_department_id}}',
            '{{%users_relation_hr_departments}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-users_relation_hr_departments-hr_department_id}}',
            '{{%users_relation_hr_departments}}',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users_relation_hr_departments-user_id}}',
            '{{%users_relation_hr_departments}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-users_relation_hr_departments-user_id}}',
            '{{%users_relation_hr_departments}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-users_relation_hr_departments-hr_department_id}}',
            '{{%users_relation_hr_departments}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-users_relation_hr_departments-hr_department_id}}',
            '{{%users_relation_hr_departments}}'
        );

        $this->dropTable('{{%users_relation_hr_departments}}');
    }
}
