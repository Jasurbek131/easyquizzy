<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rename_hr_positon_id_column_to_hr_employee_rel_positon}}`.
 */
class m220203_055646_create_rename_hr_positon_id_column_to_hr_employee_rel_positon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('hr_employee_rel_position','hr_positon_id','hr_position_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('hr_employee_rel_position','hr_position_id','hr_positon_id');
    }
}
