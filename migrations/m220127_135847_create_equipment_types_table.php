<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%equipment_types}}`.
 */
class m220127_135847_create_equipment_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%equipment_types}}', [
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
            '{{%idx-equipment_types-status_id}}',
            '{{%equipment_types}}',
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
            '{{%idx-equipment_types-status_id}}',
            '{{%equipment_types}}'
        );

        $this->dropTable('{{%equipment_types}}');
    }
}
