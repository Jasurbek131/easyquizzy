<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%auth_item}}`.
 */
class m220303_063003_add_role_type_column_to_auth_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%auth_item}}', 'role_type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%auth_item}}', 'role_type');
    }
}
