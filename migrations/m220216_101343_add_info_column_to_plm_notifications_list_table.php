<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_notifications_list}}`.
 */
class m220216_101343_add_info_column_to_plm_notifications_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_notifications_list}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_notifications_list}}', 'add_info');
    }
}
