<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%equipment_group}}`.
 */
class m220127_153003_create_equipment_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%equipment_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-equipment_group-status_id}}',
            '{{%equipment_group}}',
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
            '{{%idx-equipment_group-status_id}}',
            '{{%equipment_group}}'
        );
        $this->dropTable('{{%equipment_group}}');
    }
}
