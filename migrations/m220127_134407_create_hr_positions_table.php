<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_positions}}`.
 */
class m220127_134407_create_hr_positions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_positions}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_positions-status_id}}',
            '{{%hr_positions}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_positions-status_id}}',
            '{{%hr_positions}}'
        );

        $this->dropTable('{{%hr_positions}}');
    }
}
