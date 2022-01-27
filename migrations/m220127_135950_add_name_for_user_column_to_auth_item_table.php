<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%auth_item}}`.
 */
class m220127_135950_add_name_for_user_column_to_auth_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%auth_item}}', 'name_for_user', $this->string()->after('name'));
        $this->addColumn('{{%auth_item}}', 'category', $this->string()->after('name_for_user'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%auth_item}}', 'category');
        $this->dropColumn('{{%auth_item}}', 'name_for_user');
    }
}
