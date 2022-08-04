<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%upsert_list_column_to_status_list}}`.
 */
class m220221_060448_create_upsert_list_column_to_status_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%status_list}}', ['id' => 4,'name_uz' => 'Tasdiqlangan', 'name_en' => 'Accepted', 'name_ru' => 'Принятый'], true);
        $this->upsert('{{%status_list}}', ['id' => 5,'name_uz' => 'Rad etilgan', 'name_en' => 'Rejected', 'name_ru' => 'Отклоненный'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
