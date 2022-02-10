<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_doc_item_type_list}}`.
 */
class m220208_092324_create_plm_doc_item_type_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_doc_item_type_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'token' => $this->string(50),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%plm_doc_item_type_list}}');
    }
}
