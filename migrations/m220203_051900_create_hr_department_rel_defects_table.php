<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_department_rel_defects}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%defects}}`
 */
class m220203_051900_create_hr_department_rel_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_department_rel_defects}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'defect_id' => $this->integer(),
            'status_id' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_defects-hr_department_id}}',
            '{{%hr_department_rel_defects}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_defects-hr_department_id}}',
            '{{%hr_department_rel_defects}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `defect_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_defects-defect_id}}',
            '{{%hr_department_rel_defects}}',
            'defect_id'
        );

        // add foreign key for table `{{%defects}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_defects-defect_id}}',
            '{{%hr_department_rel_defects}}',
            'defect_id',
            '{{%defects}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_defects-status_id}}',
            '{{%hr_department_rel_defects}}',
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
            '{{%fk-hr_department_rel_defects-hr_department_id}}',
            '{{%hr_department_rel_defects}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_defects-hr_department_id}}',
            '{{%hr_department_rel_defects}}'
        );

        // drops foreign key for table `{{%defects}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_rel_defects-defect_id}}',
            '{{%hr_department_rel_defects}}'
        );

        // drops index for column `defect_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_defects-defect_id}}',
            '{{%hr_department_rel_defects}}'
        );
        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_defects-status_id}}',
            '{{%hr_department_rel_defects}}'
        );

        $this->dropTable('{{%hr_department_rel_defects}}');
    }
}
