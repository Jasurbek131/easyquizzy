<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status_list}}`.
 */
class m220127_072553_create_status_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status_list}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
        ]);
        $this->upsert('{{%status_list}}', ['id' => 1,'name_uz' => 'Faol', 'name_en' => 'Active', 'name_ru' => 'Активный'], true);
        $this->upsert('{{%status_list}}', ['id' => 2,'name_uz' => 'Faol emas', 'name_en' => 'Inactive', 'name_ru' => 'Неактивный'], true);
        $this->upsert('{{%status_list}}', ['id' => 3,'name_uz' => 'Saqlangan', 'name_en' => 'Saved', 'name_ru' => 'Сохранено'], true);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status_list}}');
    }
}
