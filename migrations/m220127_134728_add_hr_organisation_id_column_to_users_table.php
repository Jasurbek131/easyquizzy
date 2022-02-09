<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_organisation_id}}`
 */
class m220127_134728_add_hr_organisation_id_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'hr_organisation_id', $this->integer());

        // creates index for column `hr_organisation_id`
        $this->createIndex(
            '{{%idx-users-hr_organisation_id}}',
            '{{%users}}',
            'hr_organisation_id'
        );

        // add foreign key for table `{{%hr_organisation_id}}`
        $this->addForeignKey(
            '{{%fk-users-hr_organisation_id}}',
            '{{%users}}',
            'hr_organisation_id',
            '{{%hr_organisations}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_organisation_id}}`
        $this->dropForeignKey(
            '{{%fk-users-hr_organisation_id}}',
            '{{%users}}'
        );

        // drops index for column `hr_organisation_id`
        $this->dropIndex(
            '{{%idx-users-hr_organisation_id}}',
            '{{%users}}'
        );

        $this->dropColumn('{{%users}}', 'hr_organisation_id');
    }
}
