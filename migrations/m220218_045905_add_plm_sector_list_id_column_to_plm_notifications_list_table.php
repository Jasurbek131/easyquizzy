<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_notifications_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_sector_list}}`
 */
class m220218_045905_add_plm_sector_list_id_column_to_plm_notifications_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_notifications_list}}', 'plm_sector_list_id', $this->integer());

        // creates index for column `plm_sector_list_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-plm_sector_list_id}}',
            '{{%plm_notifications_list}}',
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
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

        $this->dropColumn('{{%plm_notifications_list}}', 'plm_sector_list_id');
    }
}
