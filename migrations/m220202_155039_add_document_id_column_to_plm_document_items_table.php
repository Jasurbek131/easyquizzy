<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_documents}}`
 */
class m220202_155039_add_document_id_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'document_id', $this->integer());

        // creates index for column `document_id`
        $this->createIndex(
            '{{%idx-plm_document_items-document_id}}',
            '{{%plm_document_items}}',
            'document_id'
        );

        // add foreign key for table `{{%plm_documents}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-document_id}}',
            '{{%plm_document_items}}',
            'document_id',
            '{{%plm_documents}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%plm_documents}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-document_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `document_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-document_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropColumn('{{%plm_document_items}}', 'document_id');
    }
}
