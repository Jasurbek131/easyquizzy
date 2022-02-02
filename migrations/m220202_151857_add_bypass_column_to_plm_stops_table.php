<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_stops}}`.
 */
class m220202_151857_add_bypass_column_to_plm_stops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_stops}}', 'bypass', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_stops}}', 'bypass');
    }
}
