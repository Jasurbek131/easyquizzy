<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 */
class m220217_052030_add_lifecycle_column_bypass_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'lifecycle', $this->integer());
        $this->addColumn('{{%plm_document_items}}', 'bypass', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_document_items}}', 'lifecycle');
        $this->dropColumn('{{%plm_document_items}}', 'bypass');
    }
}
