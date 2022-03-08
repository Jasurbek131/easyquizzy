<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delete_some}}`.
 */
class m220224_093401_create_delete_some_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%plm_sector_list}}`

        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `defect_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}'
        );
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `reason_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}'
        );
        $this->dropColumn('plm_sector_rel_hr_department','plm_sector_list_id');
        $this->dropColumn('plm_notifications_list','plm_sector_list_id');
        $this->dropColumn('plm_notifications_list','defect_id');
        $this->dropColumn('plm_notifications_list','reason_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('plm_notifications_list','reason_id',$this->integer());
        $this->addColumn('plm_notifications_list','defect_id',$this->integer());
        $this->addColumn('plm_notifications_list','plm_sector_list_id',$this->integer());
        $this->addColumn('plm_sector_rel_hr_department','plm_sector_list_id',$this->integer());

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}',
            'defect_id',
            '{{%defects}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `defect_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-defect_id}}',
            '{{%plm_notifications_list}}',
            'defect_id'
        );
        // add foreign key for table `{{%reasons}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}',
            'reason_id',
            '{{%reasons}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `reason_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-reason_id}}',
            '{{%plm_notifications_list}}',
            'reason_id'
        );
    }
}
