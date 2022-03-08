<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_documents}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m220210_134532_add_organisation_id_column_to_plm_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_documents}}', 'organisation_id', $this->integer());

        // creates index for column `organisation_id`
        $this->createIndex(
            '{{%idx-plm_documents-organisation_id}}',
            '{{%plm_documents}}',
            'organisation_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-plm_documents-organisation_id}}',
            '{{%plm_documents}}',
            'organisation_id',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-plm_documents-organisation_id}}',
            '{{%plm_documents}}'
        );

        // drops index for column `organisation_id`
        $this->dropIndex(
            '{{%idx-plm_documents-organisation_id}}',
            '{{%plm_documents}}'
        );

        $this->dropColumn('{{%plm_documents}}', 'organisation_id');
    }
}
