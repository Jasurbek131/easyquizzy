<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%equipments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%equipment_types}}`
 */
class m220127_140121_create_equipments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%equipments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'equipment_type_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-equipments-status_id}}',
            '{{%equipments}}',
            'status_id'
        );

        // creates index for column `equipment_type_id`
        $this->createIndex(
            '{{%idx-equipments-equipment_type_id}}',
            '{{%equipments}}',
            'equipment_type_id'
        );

        // add foreign key for table `{{%equipment_types}}`
        $this->addForeignKey(
            '{{%fk-equipments-equipment_type_id}}',
            '{{%equipments}}',
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
        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-equipments-status_id}}',
            '{{%equipments}}'
        );

        // drops foreign key for table `{{%equipment_types}}`
        $this->dropForeignKey(
            '{{%fk-equipments-equipment_type_id}}',
            '{{%equipments}}'
        );

        // drops index for column `equipment_type_id`
        $this->dropIndex(
            '{{%idx-equipments-equipment_type_id}}',
            '{{%equipments}}'
        );

        $this->dropTable('{{%equipments}}');
    }
}
