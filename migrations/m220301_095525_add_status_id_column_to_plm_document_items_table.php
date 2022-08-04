<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 */
class m220301_095525_add_status_id_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'status_id', $this->integer()->defaultValue(1)->notNull());

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_document_items-status_id}}',
            '{{%plm_document_items}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-status_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropColumn('{{%plm_document_items}}', 'status_id');
    }
}
