<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%upsert_plm_sector_list}}`.
 */
class m220215_101851_create_upsert_plm_sector_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%plm_sector_list}}', ['name_uz' => 'Ish vaqti', 'name_ru' => 'Рабочее время','status_id' => 1], true);
        $this->upsert('{{%plm_sector_list}}', ['name_uz' => 'Ta\'mirlangan', 'name_ru' => 'Отремонтированный','status_id' => 1], true);
        $this->upsert('{{%plm_sector_list}}', ['name_uz' => 'Yaroqsiz', 'name_ru' => 'Недействительный','status_id' => 1], true);
        $this->upsert('{{%plm_sector_list}}', ['name_uz' => 'Rejali to\'xtalish', 'name_ru' => 'Запланированный','status_id' => 1], true);
        $this->upsert('{{%plm_sector_list}}', ['name_uz' => 'Rejasiz to\'xtalish', 'name_ru' => 'Незапланированный','status_id' => 1], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
