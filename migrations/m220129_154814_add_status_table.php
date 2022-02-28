<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_lifecycle}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%equipment_group}}`
 * - `{{%time_types_list}}`
 */
class m220129_154814_add_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%status_list}}', ['id' => 1,'name_uz' => 'Faol', 'name_en' => 'Active', 'name_ru' => 'Активный'], true);
        $this->upsert('{{%status_list}}', ['id' => 2,'name_uz' => 'Faol emas', 'name_en' => 'Inactive', 'name_ru' => 'Неактивный'], true);
        $this->upsert('{{%status_list}}', ['id' => 3,'name_uz' => 'Saqlangan', 'name_en' => 'Saved', 'name_ru' => 'Сохранено'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
