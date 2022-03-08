<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_sector_list}}`.
 */
class m220215_165812_add_token_column_to_plm_sector_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_sector_list}}', 'token', $this->string(255));
        $this->upsert('{{%plm_sector_list}}', ['id' => 1, 'token' => 'WORKING_TIME'], true);
        $this->upsert('{{%plm_sector_list}}', ['id' => 2, 'token' => 'REPAIRED'], true);
        $this->upsert('{{%plm_sector_list}}', ['id' => 3, 'token' => 'INVALID'], true);
        $this->upsert('{{%plm_sector_list}}', ['id' => 4, 'token' => 'PLANNED'], true);
        $this->upsert('{{%plm_sector_list}}', ['id' => 5, 'token' => 'UNPLANNED'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_sector_list}}', 'token');
    }
}
