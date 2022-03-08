<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_logs}}`.
 */
class m220208_135559_create_admin_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_logs}}', [
            'id' => $this->primaryKey(),
            'old_attribute' => $this->json(),
            'new_attribute' => $this->json(),
            'table_name' => $this->string(),
            'class_name' => $this->string(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_logs}}');
    }
}
