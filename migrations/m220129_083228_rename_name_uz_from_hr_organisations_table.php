<?php

use yii\db\Migration;

/**
 * Class m220129_083228_rename_name_uz_from_hr_organisations_table
 */
class m220129_083228_rename_name_uz_from_hr_organisations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('hr_organisations','name_uz','name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('hr_organisations','name','name_uz');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220129_083228_rename_name_uz_from_hr_organisations_table cannot be reverted.\n";

        return false;
    }
    */
}
