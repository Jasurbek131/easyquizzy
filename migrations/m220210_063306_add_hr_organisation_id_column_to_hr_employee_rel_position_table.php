<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee_rel_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m220210_063306_add_hr_organisation_id_column_to_hr_employee_rel_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee_rel_position}}', 'hr_organisation_id', $this->integer());

        // creates index for column `hr_organisation_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_position-hr_organisation_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_organisation_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_position-hr_organisation_id}}',
            '{{%hr_employee_rel_position}}',
            'hr_organisation_id',
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
            '{{%fk-hr_employee_rel_position-hr_organisation_id}}',
            '{{%hr_employee_rel_position}}'
        );

        // drops index for column `hr_organisation_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_position-hr_organisation_id}}',
            '{{%hr_employee_rel_position}}'
        );

        $this->dropColumn('{{%hr_employee_rel_position}}', 'hr_organisation_id');
    }
}
