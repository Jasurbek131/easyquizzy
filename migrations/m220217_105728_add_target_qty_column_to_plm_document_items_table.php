<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 */
class m220217_105728_add_target_qty_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'target_qty', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_document_items}}', 'target_qty');
    }
}
