<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%references_product_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%status_list}}`
 */
class m220215_074413_create_references_product_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references_product_group}}', [
            'id' => $this->primaryKey(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-references_product_group-status_id}}',
            '{{%references_product_group}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-references_product_group-status_id}}',
            '{{%references_product_group}}'
        );

        $this->dropTable('{{%references_product_group}}');
    }
}
