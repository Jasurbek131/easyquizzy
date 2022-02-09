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

        $q = $this->db->createCommand("SELECT EXISTS (SELECT FROM pg_tables WHERE schemaname = 'public' AND tablename  = 'plm_unplanned_stop' );")->queryScalar();
        if ($q) {
            $this->dropTable('{{%plm_unplanned_stop}}');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('plm_stops','plm_planned_stop');
        $this->dropColumn('{{%plm_planned_stop}}', 'stopping_type');
    }
}
