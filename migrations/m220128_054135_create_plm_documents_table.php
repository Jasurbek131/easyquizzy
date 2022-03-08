<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plm_documents}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m220128_054135_create_plm_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plm_documents}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(255),
            'reg_date' => $this->datetime(),
            'hr_department_id' => $this->integer(),
            'add_info' => $this->text(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-plm_documents-hr_department_id}}',
            '{{%plm_documents}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-plm_documents-hr_department_id}}',
            '{{%plm_documents}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-plm_documents-status_id}}',
            '{{%plm_documents}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-plm_documents-hr_department_id}}',
            '{{%plm_documents}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-plm_documents-hr_department_id}}',
            '{{%plm_documents}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-plm_documents-status_id}}',
            '{{%plm_documents}}'
        );

        $this->dropTable('{{%plm_documents}}');
    }
}
