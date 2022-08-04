<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_documents}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shifts}}`
 */
class m220208_045912_add_shift_id_column_to_plm_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_documents}}', 'shift_id', $this->integer());

        // creates index for column `shift_id`
        $this->createIndex(
            '{{%idx-plm_documents-shift_id}}',
            '{{%plm_documents}}',
            'shift_id'
        );

        // add foreign key for table `{{%shifts}}`
        $this->addForeignKey(
            '{{%fk-plm_documents-shift_id}}',
            '{{%plm_documents}}',
            'shift_id',
            '{{%shifts}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%shifts}}`
        $this->dropForeignKey(
            '{{%fk-plm_documents-shift_id}}',
            '{{%plm_documents}}'
        );

        // drops index for column `shift_id`
        $this->dropIndex(
            '{{%idx-plm_documents-shift_id}}',
            '{{%plm_documents}}'
        );

        $this->dropColumn('{{%plm_documents}}', 'shift_id');
    }
}
