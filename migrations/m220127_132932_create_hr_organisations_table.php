<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr-organisations}}`.
 */
class m220127_132932_create_hr_organisations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr-organisations}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'slug' => $this->string(255),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr-organisations-status_id}}',
            '{{%hr-organisations}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hr-organisations}}');
    }
}
