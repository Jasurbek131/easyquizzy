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
class m220128_154814_add_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%status_list}}', ['id' => 1,'name_uz' => 'FAOL', 'name_en' => 'ACTIVE', 'name_ru' => 'АКТИВНЫЙ'], true);
        $this->upsert('{{%status_list}}', ['id' => 2,'name_uz' => 'FAOL_EMAS', 'name_en' => 'INACTIVE', 'name_ru' => 'НЕАКТИВНЫЙ'], true);
        $this->upsert('{{%status_list}}', ['id' => 3,'name_uz' => 'SAQLANGAN', 'name_en' => 'SAVED', 'name_ru' => 'СОХРАНЕНО'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
