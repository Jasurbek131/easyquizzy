<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_stops}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_stops}}`
 */
class m220201_161546_add_reason_id_column_to_plm_stops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_stops}}', 'reason_id', $this->integer());

        // creates index for column `reason_id`
        $this->createIndex(
            '{{%idx-plm_stops-reason_id}}',
            '{{%plm_stops}}',
            'reason_id'
        );

        // add foreign key for table `{{%plm_stops}}`
        $this->addForeignKey(
            '{{%fk-plm_stops-reason_id}}',
            '{{%plm_stops}}',
            'reason_id',
            '{{%plm_stops}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_stops}}`
        $this->dropForeignKey(
            '{{%fk-plm_stops-reason_id}}',
            '{{%plm_stops}}'
        );

        // drops index for column `reason_id`
        $this->dropIndex(
            '{{%idx-plm_stops-reason_id}}',
            '{{%plm_stops}}'
        );

        $this->dropColumn('{{%plm_stops}}', 'reason_id');
    }
}
