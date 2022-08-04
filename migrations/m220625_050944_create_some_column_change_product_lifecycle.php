<?php

use yii\db\Migration;

/**
 * Class m220625_050944_create_some_column_change_product_lifecycle
 */
class m220625_050944_create_some_column_change_product_lifecycle extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%product_lifecycle}}','lifecycle', $this->double());
        $this->alterColumn('{{%product_lifecycle}}','bypass', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220625_050944_create_some_column_change_product_lifecycle cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220625_050944_create_some_column_change_product_lifecycle cannot be reverted.\n";

        return false;
    }
    */
}
