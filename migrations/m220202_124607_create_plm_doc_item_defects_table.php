<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_doc_item_defects}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 * - `{{%defects}}`
 */
class m220202_124607_create_plm_doc_item_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_doc_item_defects}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(),
            'doc_item_id' => $this->integer(),
            'defect_id' => $this->integer(),
            'qty' => $this->smallInteger(),
            'status_id' => $this->integer(),
        ]);

        // creates index for column `doc_item_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_defects-doc_item_id}}',
            '{{%plm_doc_item_defects}}',
            'doc_item_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_defects-doc_item_id}}',
            '{{%plm_doc_item_defects}}',
            'doc_item_id',
            '{{%plm_document_items}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `defect_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_defects-defect_id}}',
            '{{%plm_doc_item_defects}}',
            'defect_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_defects-defect_id}}',
            '{{%plm_doc_item_defects}}',
            'defect_id',
            '{{%defects}}',
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
            '{{%fk-plm_doc_item_defects-doc_item_id}}',
            '{{%plm_doc_item_defects}}'
        );

        // drops index for column `doc_item_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_defects-doc_item_id}}',
            '{{%plm_doc_item_defects}}'
        );

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_doc_item_defects-defect_id}}',
            '{{%plm_doc_item_defects}}'
        );

        // drops index for column `defect_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_defects-defect_id}}',
            '{{%plm_doc_item_defects}}'
        );

        $this->dropTable('{{%plm_doc_item_defects}}');
    }
}
