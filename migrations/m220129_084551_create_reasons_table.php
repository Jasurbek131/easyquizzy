<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reasons}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%categories}}`
 */
class m220129_084551_create_reasons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reasons}}', [
            'id' => $this->primaryKey(),
            'name_uz' => $this->string(255),
            'name_ru' => $this->string(255),
            'category_id' => $this->integer(),
            'status_id' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-reasons-category_id}}',
            '{{%reasons}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-reasons-category_id}}',
            '{{%reasons}}',
            'category_id',
            '{{%categories}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-reasons-status_id}}',
            '{{%reasons}}',
            'status_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%categories}}`
        $this->dropForeignKey(
            '{{%fk-reasons-category_id}}',
            '{{%reasons}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-reasons-category_id}}',
            '{{%reasons}}'
        );

        // drop index for column `status_id`
        $this->dropIndex(
            '{{%idx-reasons-status_id}}',
            '{{%reasons}}'
        );

        $this->dropTable('{{%reasons}}');
    }
}
