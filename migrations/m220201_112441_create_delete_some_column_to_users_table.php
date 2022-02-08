<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delete_some_column_to_users}}`.
 */
class m220201_112441_create_delete_some_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%users}}', 'hr_organisation_id');
        $this->dropColumn('{{%hr_departments}}', 'hr_organisation_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%users}}', 'hr_organisation_id',$this->integer());
        $this->addColumn('{{%hr_departments}}', 'hr_organisation_id',$this->integer());
    }
}
