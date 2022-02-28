<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_sector_list}}`.
 */
class m220215_101239_create_plm_sector_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_sector_list}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->createIndex('{{%plm_sector_list-status_id}}',
            'plm_sector_list',
            'status_id'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_sector_list-status_id}}',
            '{{%plm_sector_list}}',
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
            '{{%idx-plm_sector_list-status_id}}',
            '{{%plm_sector_list}}'
        );

        $this->dropTable('{{%plm_sector_list}}');
    }
}
