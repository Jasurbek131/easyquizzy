<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_sector_rel_hr_department}}`.
 */
class m220624_145648_add_type_column_to_plm_sector_rel_hr_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_sector_rel_hr_department}}', 'type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_sector_rel_hr_department}}', 'type');
    }
}
