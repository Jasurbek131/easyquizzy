<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_departments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m220204_171306_add_parent_id_column_to_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_departments}}', 'parent_id', $this->integer());

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-hr_departments-parent_id}}',
            '{{%hr_departments}}',
            'parent_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_departments-parent_id}}',
            '{{%hr_departments}}',
            'parent_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_departments-parent_id}}',
            '{{%hr_departments}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-hr_departments-parent_id}}',
            '{{%hr_departments}}'
        );

        $this->dropColumn('{{%hr_departments}}', 'parent_id');
    }
}
