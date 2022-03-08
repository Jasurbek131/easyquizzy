<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_group}}`
 */
class m220203_061918_add_equipment_group_id_column_to_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_document_items}}', 'equipment_group_id', $this->integer());

        // creates index for column `equipment_group_id`
        $this->createIndex(
            '{{%idx-plm_document_items-equipment_group_id}}',
            '{{%plm_document_items}}',
            'equipment_group_id'
        );

        // add foreign key for table `{{%equipment_group}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-equipment_group_id}}',
            '{{%plm_document_items}}',
            'equipment_group_id',
            '{{%equipment_group}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%equipment_group}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-equipment_group_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `equipment_group_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-equipment_group_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropColumn('{{%plm_document_items}}', 'equipment_group_id');
    }
}
