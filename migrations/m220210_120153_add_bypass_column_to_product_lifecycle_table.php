<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_lifecycle}}`.
 */
class m220210_120153_add_bypass_column_to_product_lifecycle_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_lifecycle}}', 'bypass', $this->integer());
        $this->addColumn('{{%equipment_group}}', 'value', $this->decimal(20,3));
        $this->addColumn('{{%shifts}}', 'value', $this->decimal(20,3));
        $this->addColumn('{{%hr_departments}}', 'value', $this->decimal(20,3));
    }

    /**shifts
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_departments}}', 'value');
        $this->dropColumn('{{%shifts}}', 'value');
        $this->dropColumn('{{%equipment_group}}', 'value');
        $this->dropColumn('{{%product_lifecycle}}', 'bypass');
    }
}
