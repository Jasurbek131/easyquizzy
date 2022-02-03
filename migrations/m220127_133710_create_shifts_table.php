<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shifts}}`.
 */
class m220127_133710_create_shifts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shifts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'start_time' => $this->time(),
            'end_time' => $this->time(),
            'code' => $this->string(50),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-shifts-status_id}}',
            '{{%shifts}}',
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
            '{{%idx-shifts-status_id}}',
            '{{%shifts}}'
        );

        $this->dropTable('{{%shifts}}');
    }
}
