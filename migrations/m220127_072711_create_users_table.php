<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%status_list}}`
 */
class m220127_072711_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'password' => $this->string(),
            'auth_key' => $this->string(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-users-status_id}}',
            '{{%users}}',
            'status_id'
        );

        // add foreign key for table `{{%status_list}}`
        $this->addForeignKey(
            '{{%fk-users-status_id}}',
            '{{%users}}',
            'status_id',
            '{{%status_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%status_list}}`
        $this->dropForeignKey(
            '{{%fk-users-status_id}}',
            '{{%users}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-users-status_id}}',
            '{{%users}}'
        );

        $this->dropTable('{{%users}}');
    }
}
