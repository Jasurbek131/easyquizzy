<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_departments}}`.
 */
class m220203_043436_add_type_some_column_to_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_departments}}', 'type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_departments}}', 'type');
    }
}
