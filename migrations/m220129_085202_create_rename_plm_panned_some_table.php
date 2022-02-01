<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rename_plm_panned_some}}`.
 */
class m220129_085202_create_rename_plm_panned_some_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('plm_planned_stop', 'plm_stops');
        $this->addColumn('{{%plm_stops}}','stopping_type',$this->smallInteger());
        // drops foreign key for table `{{%plm_documents}}`
        $this->dropForeignKey(
            '{{%fk-plm_unplanned_stop-doc_id}}',
            '{{%plm_unplanned_stop}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-plm_unplanned_stop-doc_id}}',
            '{{%plm_unplanned_stop}}'
        );
        $this->dropIndex(
            '{{%idx-plm_unplanned_stop-status_id}}',
            '{{%plm_unplanned_stop}}'
        );
        $this->dropTable('{{%plm_unplanned_stop}}');

    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('plm_stops','plm_planned_stop');
    }
}
