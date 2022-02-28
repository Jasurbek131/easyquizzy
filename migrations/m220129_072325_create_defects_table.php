<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%defects}}`.
 */
class m220129_072325_create_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%defects}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'type' => $this->smallInteger(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-defects-status_id}}',
            '{{%defects}}',
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
            '{{%idx-defects-status_id}}',
            '{{%defects}}'
        );

        $this->dropTable('{{%defects}}');
    }
}
