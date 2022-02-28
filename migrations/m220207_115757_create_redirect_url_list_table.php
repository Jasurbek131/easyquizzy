<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%redirect_url_list}}`.
 */
class m220207_115757_create_redirect_url_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%redirect_url_list}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(),
            'name_ru' => $this->string(),
            'url' => $this->string(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-redirect_url_list-status_id}}',
            '{{%redirect_url_list}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // creates index for column `status_id`
        $this->dropIndex(
            '{{%idx-redirect_url_list-status_id}}',
            '{{%redirect_url_list}}'
        );

        $this->dropTable('{{%redirect_url_list}}');
    }
}
