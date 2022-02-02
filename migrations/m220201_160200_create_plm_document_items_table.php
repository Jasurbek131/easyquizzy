<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%plm_stops}}`
 * - `{{%plm_stops}}`
 * - `{{%defects}}`
 * - `{{%defects}}`
 * - `{{%plm_processing_time}}`
 */
class m220201_160200_create_plm_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_document_items}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'planned_stop_id' => $this->integer(),
            'unplanned_stop_id' => $this->integer(),
            'repaired_id' => $this->integer(),
            'scrapped_id' => $this->integer(),
            'processing_time_id' => $this->integer(),
            'qty' => $this->integer(),
            'fact_qty' => $this->integer(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-plm_document_items-product_id}}',
            '{{%plm_document_items}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-product_id}}',
            '{{%plm_document_items}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

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
            'CASCADE'
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
            'CASCADE'
        );

        // creates index for column `repaired_id`
        $this->createIndex(
            '{{%idx-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}',
            'repaired_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}',
            'repaired_id',
            '{{%defects}}',
            'id',
            'CASCADE'
        );

        // creates index for column `scrapped_id`
        $this->createIndex(
            '{{%idx-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}',
            'scrapped_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}',
            'scrapped_id',
            '{{%defects}}',
            'id',
            'CASCADE'
        );

        // creates index for column `processing_time_id`
        $this->createIndex(
            '{{%idx-plm_document_items-processing_time_id}}',
            '{{%plm_document_items}}',
            'processing_time_id'
        );

        // add foreign key for table `{{%plm_processing_time}}`
        $this->addForeignKey(
            '{{%fk-plm_document_items-processing_time_id}}',
            '{{%plm_document_items}}',
            'processing_time_id',
            '{{%plm_processing_time}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-product_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-product_id}}',
            '{{%plm_document_items}}'
        );

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

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `repaired_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-repaired_id}}',
            '{{%plm_document_items}}'
        );

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `scrapped_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-scrapped_id}}',
            '{{%plm_document_items}}'
        );

        // drops foreign key for table `{{%plm_processing_time}}`
        $this->dropForeignKey(
            '{{%fk-plm_document_items-processing_time_id}}',
            '{{%plm_document_items}}'
        );

        // drops index for column `processing_time_id`
        $this->dropIndex(
            '{{%idx-plm_document_items-processing_time_id}}',
            '{{%plm_document_items}}'
        );

        $this->dropTable('{{%plm_document_items}}');
    }
}
