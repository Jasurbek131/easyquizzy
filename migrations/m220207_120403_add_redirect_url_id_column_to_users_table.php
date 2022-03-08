<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%redirect_url_list}}`
 */
class m220207_120403_add_redirect_url_id_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'redirect_url_id', $this->integer());

        // creates index for column `redirect_url_id`
        $this->createIndex(
            '{{%idx-users-redirect_url_id}}',
            '{{%users}}',
            'redirect_url_id'
        );

        // add foreign key for table `{{%redirect_url_list}}`
        $this->addForeignKey(
            '{{%fk-users-redirect_url_id}}',
            '{{%users}}',
            'redirect_url_id',
            '{{%redirect_url_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%redirect_url_list}}`
        $this->dropForeignKey(
            '{{%fk-users-redirect_url_id}}',
            '{{%users}}'
        );

        // drops index for column `redirect_url_id`
        $this->dropIndex(
            '{{%idx-users-redirect_url_id}}',
            '{{%users}}'
        );

        $this->dropColumn('{{%users}}', 'redirect_url_id');
    }
}
