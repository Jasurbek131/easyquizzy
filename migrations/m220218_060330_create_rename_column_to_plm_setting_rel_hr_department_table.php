<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rename_column_to_plm_setting_rel_hr_department}}`.
 */
class m220218_060330_create_rename_column_to_plm_setting_rel_hr_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%plm_setting_accepted_sector_rel_hr_department}}', 'plm_sector_rel_hr_department');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%plm_sector_rel_hr_department}}', 'plm_setting_accepted_sector_rel_hr_department');

    }
}
