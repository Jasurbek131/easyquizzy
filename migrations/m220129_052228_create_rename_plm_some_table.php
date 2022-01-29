<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rename_plm_some}}`.
 */
class m220129_052228_create_rename_plm_some_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('plm_scheduled_stop', 'plm_planned_stop');
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('plm_planned_stop','plm_scheduled_stop');
    }
}
