<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%equipment_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_types}}`
 */
class m220307_112519_add_equipment_type_id_column_to_equipment_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%equipment_group}}', 'equipment_type_id', $this->integer());

        // creates index for column `equipment_type_id`
        $this->createIndex(
            '{{%idx-equipment_group-equipment_type_id}}',
            '{{%equipment_group}}',
            'equipment_type_id'
        );

        // add foreign key for table `{{%equipment_types}}`
        $this->addForeignKey(
            '{{%fk-equipment_group-equipment_type_id}}',
            '{{%equipment_group}}',
            'equipment_type_id',
            '{{%equipment_types}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%equipment_types}}`
        $this->dropForeignKey(
            '{{%fk-equipment_group-equipment_type_id}}',
            '{{%equipment_group}}'
        );

        // drops index for column `equipment_type_id`
        $this->dropIndex(
            '{{%idx-equipment_group-equipment_type_id}}',
            '{{%equipment_group}}'
        );

        $this->dropColumn('{{%equipment_group}}', 'equipment_type_id');
    }
}
