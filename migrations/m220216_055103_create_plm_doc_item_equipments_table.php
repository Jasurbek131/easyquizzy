<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_doc_item_equipments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%plm_document_items}}`
 * - `{{%equipments}}`
 */
class m220216_055103_create_plm_doc_item_equipments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_doc_item_equipments}}', [
            'id' => $this->primaryKey(),
            'document_item_id' => $this->integer(),
            'equipment_id' => $this->integer(),
        ]);

        // creates index for column `document_item_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_equipments-document_item_id}}',
            '{{%plm_doc_item_equipments}}',
            'document_item_id'
        );

        // add foreign key for table `{{%plm_document_items}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_equipments-document_item_id}}',
            '{{%plm_doc_item_equipments}}',
            'document_item_id',
            '{{%plm_document_items}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `equipment_id`
        $this->createIndex(
            '{{%idx-plm_doc_item_equipments-equipment_id}}',
            '{{%plm_doc_item_equipments}}',
            'equipment_id'
        );

        // add foreign key for table `{{%equipments}}`
        $this->addForeignKey(
            '{{%fk-plm_doc_item_equipments-equipment_id}}',
            '{{%plm_doc_item_equipments}}',
            'equipment_id',
            '{{%equipments}}',
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
            '{{%fk-plm_doc_item_equipments-document_item_id}}',
            '{{%plm_doc_item_equipments}}'
        );

        // drops index for column `document_item_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_equipments-document_item_id}}',
            '{{%plm_doc_item_equipments}}'
        );

        // drops foreign key for table `{{%equipments}}`
        $this->dropForeignKey(
            '{{%fk-plm_doc_item_equipments-equipment_id}}',
            '{{%plm_doc_item_equipments}}'
        );

        // drops index for column `equipment_id`
        $this->dropIndex(
            '{{%idx-plm_doc_item_equipments-equipment_id}}',
            '{{%plm_doc_item_equipments}}'
        );

        $this->dropTable('{{%plm_doc_item_equipments}}');
    }
}
