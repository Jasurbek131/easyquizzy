<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%some_column_to_other}}`.
 */
class m220217_135409_create_some_column_to_other_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plm_notification_rel_defect}}', 'defect_count', $this->integer());
        $this->addColumn('{{%defects}}', 'hr_department_id', $this->integer());
        $this->addColumn('{{%categories}}', 'hr_department_id', $this->integer());
        $this->addColumn('{{%reasons}}', 'hr_department_id', $this->integer());

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-defects-hr_department_id}}',
            '{{%defects}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_department_id}}`
        $this->addForeignKey(
            '{{%fk-defects-hr_department_id}}',
            '{{%defects}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-categories-hr_department_id}}',
            '{{%categories}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_department_id}}`
        $this->addForeignKey(
            '{{%fk-categories-hr_department_id}}',
            '{{%categories}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-reasons-hr_department_id}}',
            '{{%reasons}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_department_id}}`
        $this->addForeignKey(
            '{{%fk-reasons-hr_department_id}}',
            '{{%reasons}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-defects-hr_department_id}}',
            '{{%defects}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-defects-hr_department_id}}',
            '{{%defects}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-categories-hr_department_id}}',
            '{{%categories}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-categories-hr_department_id}}',
            '{{%categories}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-reasons-hr_department_id}}',
            '{{%reasons}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-reasons-hr_department_id}}',
            '{{%reasons}}'
        );

        $this->dropColumn('{{%plm_notification_rel_defect}}', 'defect_count');
        $this->dropColumn('{{%defects}}', 'hr_department_id');
        $this->dropColumn('{{%categories}}', 'hr_department_id');
        $this->dropColumn('{{%reasons}}', 'hr_department_id');
    }
}
