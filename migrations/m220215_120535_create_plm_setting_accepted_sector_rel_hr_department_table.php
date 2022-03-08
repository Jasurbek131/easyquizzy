<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_setting_accepted_sector_rel_hr_department}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%plm_sector_list}}`
 */
class m220215_120535_create_plm_setting_accepted_sector_rel_hr_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_setting_accepted_sector_rel_hr_department}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'plm_sector_list_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-hr_department_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-hr_department_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `plm_sector_list_id`
        $this->createIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-plm_sector_list_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}',
            'plm_sector_list_id'
        );

        // add foreign key for table `{{%plm_sector_list}}`
        $this->addForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-plm_sector_list_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}',
            'plm_sector_list_id',
            '{{%plm_sector_list}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-status_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-hr_department_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-hr_department_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}'
        );

        // drops foreign key for table `{{%plm_sector_list}}`
        $this->dropForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-plm_sector_list_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}'
        );

        // drops index for column `plm_sector_list_id`
        $this->dropIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-plm_sector_list_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}'
        );

        // creates index for column `status_id`
        $this->dropIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-status_id}}',
            '{{%plm_setting_accepted_sector_rel_hr_department}}'
        );

        $this->dropTable('{{%plm_setting_accepted_sector_rel_hr_department}}');
    }
}
