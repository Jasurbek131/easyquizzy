<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plm_sector_rel_hr_department}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%categories}}`
 */
class m220223_112249_add_category_id_column_to_plm_sector_rel_hr_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'token', $this->string());
        $this->addColumn('{{%plm_sector_rel_hr_department}}', 'category_id', $this->integer());

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-plm_sector_rel_hr_department-category_id}}',
            '{{%plm_sector_rel_hr_department}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-plm_sector_rel_hr_department-category_id}}',
            '{{%plm_sector_rel_hr_department}}',
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
            '{{%fk-plm_sector_rel_hr_department-category_id}}',
            '{{%plm_sector_rel_hr_department}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-plm_sector_rel_hr_department-category_id}}',
            '{{%plm_sector_rel_hr_department}}'
        );

        $this->dropColumn('{{%plm_sector_rel_hr_department}}', 'category_id');
        $this->dropColumn('{{%categories}}', 'token');

    }
}
