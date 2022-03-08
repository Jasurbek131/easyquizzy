<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%time_types_list}}`.
 */
class m220127_134442_create_time_types_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%time_types_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'code' => $this->integer(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-time_types_list-status_id}}',
            '{{%time_types_list}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-time_types_list-status_id}}',
            '{{%time_types_list}}'
        );

        $this->dropTable('{{%time_types_list}}');
    }
}
