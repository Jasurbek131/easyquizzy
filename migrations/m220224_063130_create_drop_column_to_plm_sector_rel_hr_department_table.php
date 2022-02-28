<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%drop_column_to_plm_sector_rel_hr_department}}`.
 */
class m220224_063130_create_drop_column_to_plm_sector_rel_hr_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        // drops foreign key for table `{{%plm_sector_list}}`
        $this->dropForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-plm_sector_lis}}',
            '{{%plm_sector_rel_hr_department}}'
        );

        // drops index for column `plm_sector_list_id`
        $this->dropIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-plm_sector_li}}',
            '{{%plm_sector_rel_hr_department}}'
        );

        // drops foreign key for table `{{%plm_sector_list}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-plm_sector_list_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `plm_sector_list_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-plm_sector_list_id}}',
            '{{%plm_notifications_list}}'
        );

        $this->dropTable('{{%plm_sector_list}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%plm_sector_list}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // add foreign key for table `{{%plm_sector_list}}`
        $this->addForeignKey(
            '{{%fk-plm_setting_accepted_sector_rel_hr_department-plm_sector_lis}}',
            '{{%plm_sector_rel_hr_department}}',
            'plm_sector_list_id',
            '{{%plm_sector_list}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `plm_sector_list_id`
        $this->createIndex(
            '{{%idx-plm_setting_accepted_sector_rel_hr_department-plm_sector_li}}',
            '{{%plm_sector_rel_hr_department}}',
            'plm_sector_list_id'
        );
        // add foreign key for table `{{%plm_sector_list}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-plm_sector_list_id}}',
            '{{%plm_notifications_list}}',
            'plm_sector_list_id',
            '{{%plm_sector_list}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `plm_sector_list_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-plm_sector_list_id}}',
            '{{%plm_notifications_list}}',
            'plm_sector_list_id'
        );

    }
}
