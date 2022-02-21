<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_stops}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 */
class m220219_072309_add_document_item_id_column_to_plm_stops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_stops}}', 'document_item_id', $this->integer());

        // creates index for column `document_item_id`
        $this->createIndex(
            '{{%idx-plm_stops-document_item_id}}',
            '{{%plm_stops}}',
            'document_item_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_stops-document_item_id}}',
            '{{%plm_stops}}',
            'document_item_id',
            '{{%plm_document_items}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_document_items}}`
        $this->dropForeignKey(
            '{{%fk-plm_stops-document_item_id}}',
            '{{%plm_stops}}'
        );

        // drops index for column `document_item_id`
        $this->dropIndex(
            '{{%idx-plm_stops-document_item_id}}',
            '{{%plm_stops}}'
        );

        $this->dropColumn('{{%plm_stops}}', 'document_item_id');
    }
}
