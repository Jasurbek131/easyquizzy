<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_departments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_organisations}}`
 */
class m220127_133553_create_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_departments}}', [
            'id' => $this->primaryKey(),
            'hr_organisation_id' => $this->integer(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'token' => $this->string(255),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_organisation_id`
        $this->createIndex(
            '{{%idx-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}',
            'hr_organisation_id'
        );

        // add foreign key for table `{{%hr_organisations}}`
        $this->addForeignKey(
            '{{%fk-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}',
            'hr_organisation_id',
            '{{%hr_organisations}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_departments-status_id}}',
            '{{%hr_departments}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_organisations}}`
        $this->dropForeignKey(
            '{{%fk-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}'
        );

        // drops index for column `hr_organisation_id`
        $this->dropIndex(
            '{{%idx-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}'
        );
        //drop index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_departments-status_id}}',
            '{{%hr_departments}}',
            'status_id'
        );

        $this->dropTable('{{%hr_departments}}');
    }
}
