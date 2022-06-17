<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%equipment_group}}`.
 */
class m220617_042528_add_repair_is_ok_column_is_plan_quantity_entered_manually_column_to_equipment_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        // drops foreign key for table `{{%plm_stops}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-planned_stop_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `planned_stop_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-planned_stop_id}}',
            '{{%plm_document_items}}'
        );

        // drops foreign key for table `{{%plm_stops}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-unplanned_stop_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `unplanned_stop_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-unplanned_stop_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropColumn('{{%plm_document_items}}', 'planned_stop_id');
        $this->dropColumn('{{%plm_document_items}}', 'unplanned_stop_id');


        $this->addColumn('{{%equipment_group}}', 'repair_is_ok', $this->boolean());
        $this->addColumn('{{%equipment_group}}', 'is_plan_quantity_entered_manually', $this->boolean());

        $this->addColumn('{{%plm_document_items}}', 'repair_is_ok', $this->boolean());
        $this->addColumn('{{%plm_document_items}}', 'is_plan_quantity_entered_manually', $this->boolean());

        $this->addColumn('{{%plm_document_items}}', 'plan_quantity_entered_manually', $this->double()->defaultValue(0));

        $this->dropColumn('{{%plm_doc_item_products}}', 'target_qty');
        $this->dropColumn('{{%plm_doc_item_products}}', 'lifecycle');
        $this->dropColumn('{{%plm_doc_item_products}}', 'bypass');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->addColumn('{{%plm_document_items}}', 'planned_stop_id', $this->integer());
        $this->addColumn('{{%plm_document_items}}', 'unplanned_stop_id', $this->integer());

        // creates index for column `planned_stop_id`
        $this->createIndex(
            '{{%idx-plm_document_items-planned_stop_id}}',
            '{{%plm_document_items}}',
            'planned_stop_id'
        );

        // add foreign key for table `{{%plm_stops}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-planned_stop_id}}',
            '{{%plm_document_items}}',
            'planned_stop_id',
            '{{%plm_stops}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `unplanned_stop_id`
        $this->createIndex(
            '{{%idx-plm_document_items-unplanned_stop_id}}',
            '{{%plm_document_items}}',
            'unplanned_stop_id'
        );

        // add foreign key for table `{{%plm_stops}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-unplanned_stop_id}}',
            '{{%plm_document_items}}',
            'unplanned_stop_id',
            '{{%plm_stops}}',
            'id',
            'RESTRICT'
        );

        $this->addColumn('{{%plm_doc_item_products}}', 'target_qty', $this->double());
        $this->addColumn('{{%plm_doc_item_products}}', 'lifecycle', $this->double());
        $this->addColumn('{{%plm_doc_item_products}}', 'bypass', $this->double());

        $this->dropColumn('{{%plm_document_items}}', 'repair_is_ok');
        $this->dropColumn('{{%plm_document_items}}', 'is_plan_quantity_entered_manually');

        $this->dropColumn('{{%plm_document_items}}', 'plan_quantity_entered_manually');

        $this->dropColumn('{{%equipment_group}}', 'repair_is_ok');
        $this->dropColumn('{{%equipment_group}}', 'is_plan_quantity_entered_manually');
    }
}
