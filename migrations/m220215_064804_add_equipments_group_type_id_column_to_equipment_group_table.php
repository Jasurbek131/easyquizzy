<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%equipment_group}}`.
 */
class m220215_064804_add_equipments_group_type_id_column_to_equipment_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%equipment_group}}', 'equipments_group_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%equipment_group}}', 'equipments_group_type_id');
    }
}
