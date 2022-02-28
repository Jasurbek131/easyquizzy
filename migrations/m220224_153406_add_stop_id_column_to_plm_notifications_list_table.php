<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_notifications_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_stops}}`
 */
class m220224_153406_add_stop_id_column_to_plm_notifications_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_notifications_list}}', 'stop_id', $this->integer());

        // creates index for column `stop_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-stop_id}}',
            '{{%plm_notifications_list}}',
            'stop_id'
        );

        // add foreign key for table `{{%plm_stops}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-stop_id}}',
            '{{%plm_notifications_list}}',
            'stop_id',
            '{{%plm_stops}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_stops}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-stop_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `stop_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-stop_id}}',
            '{{%plm_notifications_list}}'
        );

        $this->dropColumn('{{%plm_notifications_list}}', 'stop_id');
    }
}
