<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_department_rel_product}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%products}}`
 */
class m220204_104332_create_hr_department_rel_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_department_rel_product}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'product_id' => $this->integer(),
            'status_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_product-hr_department_id}}',
            '{{%hr_department_rel_product}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_product-hr_department_id}}',
            '{{%hr_department_rel_product}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_product-product_id}}',
            '{{%hr_department_rel_product}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-hr_department_rel_product-product_id}}',
            '{{%hr_department_rel_product}}',
            'product_id',
            '{{%products}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-hr_department_rel_product-status_id}}',
            '{{%hr_department_rel_product}}',
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
            '{{%fk-hr_department_rel_product-hr_department_id}}',
            '{{%hr_department_rel_product}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_product-hr_department_id}}',
            '{{%hr_department_rel_product}}'
        );

        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_rel_product-product_id}}',
            '{{%hr_department_rel_product}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_product-product_id}}',
            '{{%hr_department_rel_product}}'
        );

        // creates index for column `status_id`
        $this->dropIndex(
            '{{%idx-hr_department_rel_product-status_id}}',
            '{{%hr_department_rel_product}}'
        );

        $this->dropTable('{{%hr_department_rel_product}}');
    }
}
