<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_notifications_list}}`.
 */
class m220222_111513_add_by_pass_column_to_plm_notifications_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_notifications_list}}', 'by_pass', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_notifications_list}}', 'by_pass');
    }
}
