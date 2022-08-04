<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%some_tree_column_to_hr_departments}}`.
 */
class m220201_104202_create_some_tree_column_to_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    const TABLE_NAME = '{{%hr_departments}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(self::TABLE_NAME,'root', $this->integer());
        $this->addColumn(self::TABLE_NAME,'lft', $this->integer());
        $this->addColumn(self::TABLE_NAME,'rgt', $this->integer());
        $this->addColumn(self::TABLE_NAME,'lvl', $this->smallInteger(5));
        $this->addColumn(self::TABLE_NAME,'icon', $this->string(255));
        $this->addColumn(self::TABLE_NAME,'icon_type', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn(self::TABLE_NAME,'active', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'selected', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'disabled', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'readonly', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'visible', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'collapsed', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'movable_u', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_d', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_l', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_r', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable_all', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'child_allowed', $this->boolean()->defaultValue(true));

        $this->createIndex('hr_departments_tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('hr_departments_tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('hr_departments_tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('hr_departments_tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('hr_departments_tree_NK5', self::TABLE_NAME, 'active');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('hr_departments_tree_NK1', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK2', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK3', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK4', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK5', self::TABLE_NAME);

        $this->dropColumn(self::TABLE_NAME, 'child_allowed');
        $this->dropColumn(self::TABLE_NAME,'root');
        $this->dropColumn(self::TABLE_NAME,'lft');
        $this->dropColumn(self::TABLE_NAME,'rgt');
        $this->dropColumn(self::TABLE_NAME,'lvl');
        $this->dropColumn(self::TABLE_NAME,'icon');
        $this->dropColumn(self::TABLE_NAME,'icon_type');
        $this->dropColumn(self::TABLE_NAME,'active');
        $this->dropColumn(self::TABLE_NAME,'selected');
        $this->dropColumn(self::TABLE_NAME,'disabled');
        $this->dropColumn(self::TABLE_NAME,'readonly');
        $this->dropColumn(self::TABLE_NAME,'visible');
        $this->dropColumn(self::TABLE_NAME,'collapsed');
        $this->dropColumn(self::TABLE_NAME,'movable_u');
        $this->dropColumn(self::TABLE_NAME,'movable_d');
        $this->dropColumn(self::TABLE_NAME,'movable_l');
        $this->dropColumn(self::TABLE_NAME,'movable_r');
        $this->dropColumn(self::TABLE_NAME,'removable');
        $this->dropColumn(self::TABLE_NAME,'removable_all');
    }
}
