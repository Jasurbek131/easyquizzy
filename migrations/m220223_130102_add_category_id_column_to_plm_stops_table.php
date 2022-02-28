<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_stops}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%categories}}`
 */
class m220223_130102_add_category_id_column_to_plm_stops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_stops}}', 'category_id', $this->integer());

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-plm_stops-category_id}}',
            '{{%plm_stops}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-plm_stops-category_id}}',
            '{{%plm_stops}}',
            'category_id',
            '{{%categories}}',
            'id',
            'RESTRICT'
        );

        $this->addColumn('{{%plm_notifications_list}}', 'category_id', $this->integer());

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-plm_notifications_list-category_id}}',
            '{{%plm_notifications_list}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-plm_notifications_list-category_id}}',
            '{{%plm_notifications_list}}',
            'category_id',
            '{{%categories}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%categories}}`
        $this->dropForeignKey(
            '{{%fk-plm_notifications_list-category_id}}',
            '{{%plm_notifications_list}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-plm_notifications_list-category_id}}',
            '{{%plm_notifications_list}}'
        );

        $this->dropColumn('{{%plm_notifications_list}}', 'category_id');

        // drops foreign key for table `{{%categories}}`
        $this->dropForeignKey(
            '{{%fk-plm_stops-category_id}}',
            '{{%plm_stops}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-plm_stops-category_id}}',
            '{{%plm_stops}}'
        );

        $this->dropColumn('{{%plm_stops}}', 'category_id');
    }
}
