<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_doc_item_products}}`.
 */
class m220221_190947_add_target_qty_column_lifecycle_column_bypass_column_to_plm_doc_item_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_doc_item_products}}', 'target_qty', $this->float());
        $this->addColumn('{{%plm_doc_item_products}}', 'lifecycle', $this->float());
        $this->addColumn('{{%plm_doc_item_products}}', 'bypass', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%plm_doc_item_products}}', 'target_qty');
        $this->dropColumn('{{%plm_doc_item_products}}', 'lifecycle');
        $this->dropColumn('{{%plm_doc_item_products}}', 'bypass');
    }
}
